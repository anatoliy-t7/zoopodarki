<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Jobs\ImportOrders;
use App\Jobs\ProcessImportProduct1C;
use App\Jobs\ProcessOffersProduct1C;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class ExchangeProductsController extends Controller
{
    protected $request;
    protected $stepCheckAuth = 'checkauth';
    protected $stepInit = 'init';
    protected $stepFile = 'file';
    protected $stepImport = 'import';
    protected $stepQuery = 'query';
    protected $stepSuccess = 'success';
    protected $parentId = null;
    protected $categoryImport = false;

    // mode: checkauth, init, file, import, query

    public function __construct(Request $request)
    {
        $this->middleware(['auth1c']);
        $this->request = $request;
    }

    public function exchange()
    {
        $type = $this->request->query('type');
        $mode = $this->request->query('mode');

        if (!$this->userLogin()) {
            return $this->failure('wrong username or password');
        } else {
            // как выяснилось - после авторизации Laravel меняет id сессии, т.о.
            // при каждом запросе от 1С будет новая сессия и если что-то туда
            // записать то это будет потеряно, поэтому берем ИД сессии, который
            // был отправлен в 1С на этапе авторизации и принудительно устанавливаем
            $cookie = $this->request->header('cookie');
            $sessionName = config('session.cookie');

            if ($cookie
                && preg_match("/${sessionName}=([^;\s]+)/", $cookie, $matches)) {
                // если убрать эту строчку и сделать вот так
                // session()->setId($matches[1]), то ИНОГДА o_O это приводит к
                // ошибке - говорит, что ничего не передано, хотя оно есть и
                // передается
                $id = $matches[1];
                session()->setId($id);
            }
        }

        switch ($mode) {
            case $this->stepCheckAuth:
                return $this->checkAuth();
            case $this->stepInit:
                return $this->init();
            case $this->stepFile:
                return $this->getFile();
            case $this->stepImport:
                return $this->parsing();
            case $this->stepQuery:
                return $this->processQuery();
            case $this->stepSuccess:
                Log::info('Sync 1C Success');
        }
    }

    /**
     * Авторизация 1с в системе.
     */
    protected function userLogin()
    {
        if (Auth::getUser() === null) {
            $user = \Request::getUser();
            $pass = \Request::getPassword();

            $attempt = Auth::attempt(['email' => $user, 'password' => $pass]);

            if (!$attempt) {
                return false;
            }

            $gates = config('protocolExchange1C.gates', []);

            if (!is_array($gates)) {
                $gates = [$gates];
            }

            foreach ($gates as $gate) {
                if (Gate::has($gate) && Gate::denies($gate, Auth::user())) {
                    Auth::logout();

                    return false;
                }
            }

            return true;
        }

        return true;
    }

    protected function checkAuth()
    {
        try {
            $cookieName = config('session.cookie');
            $cookieID = Session::getId();

            return $this->answer("success\n${cookieName}\n${cookieID}");
        } catch (\Throwable $th) {
            Log::error('stepCheckAuth');
            Log::error($th);
        }
    }

    /**
     * Инициализация соединения.
     * @return string
     */
    protected function init()
    {
        try {
            $zip = 'zip=' . ($this->canUseZip() ? 'yes' : 'no');
            $maxFileSize = 'file_limit=' . (10 * 1000 * 1024);

            return $this->answer("${zip}\n${maxFileSize}");
        } catch (\Throwable $th) {
            Log::error('stepInit');
            Log::error($th);
        }
    }

    /**
     * Можно ли использовать ZIP.
     * @return bool
     */
    protected function canUseZip()
    {
        return function_exists('zip_open');
    }

    /**
     * Получение файла(ов).
     * @return string
     */
    protected function getFile()
    {
        try {
            $filename = preg_replace('#^(/tmp/|upload/1c/webdata)#', '', $this->request->get('filename'));
            $filename = trim(str_replace('\\', '/', trim($filename)), '/');

            if (empty($filename)) {
                Log::error('filename is empty');

                return $this->failure('mode: ' . $this->stepFile
                . ', filename is empty');
            }

            $dir = storage_path('app/sync');

            if (!file_exists($dir)) {
                mkdir($dir, 0777, true);
            }

            $filePath = $dir . '/' . $filename;

            $file = fopen($filePath, 'ab');

            $data = file_get_contents('php://input');

            $result = fwrite($file, $data);

            $size = strlen($data);

            if ($result !== $size) {
                return $this->failure("Ошибка записи файла: ${filePath}");
            }

            if (substr($filePath, -3) == 'zip') {
                if (!$this->unzip($dir, $filePath)) {
                    return $this->failure("Не удалось распаковать архив: ${filePath}");
                } else {
                    unlink($filePath); // удаление архива
                }
            }

            return $this->answer('success');
        } catch (\Throwable $th) {
            Log::error('step getFile');
            Log::error($th);
        }
    }

    protected function unzip($dir, $filePath)
    {
        if (class_exists('ZipArchive')) {
            $zip = new \ZipArchive();

            if ($zip->open($filePath) === true) {
                $zip->extractTo($dir);

                for ($i = 0; $i < $zip->numFiles; $i++) {
                    $filename = $zip->getNameIndex($i);

                    if (Str::endsWith($filename, '.xml')) {
                        session(['filename' => $filename]);
                    } else {
                        $this->failure('Внутри архива не было XML файла');
                        Log::error('Внутри архива не было XML файла');
                    }
                }
                $zip->close();
            } else {
                Log::error($zip->getStatusString());
            }
        }

        return $this->answer('success');
    }

    /**
     * Сообщение о ошибке.
     * @param string $details - детали, строки должны быть разделены /n
     */
    protected function failure($details = '')
    {
        $return = "failure\n" . $details;

        return $this->answer($return);
    }

    // Ответ серверу
    protected function answer($answer)
    {
        return iconv('UTF-8', 'windows-1251', $answer);
    }

    protected function parsing()
    {
        try {
            $filename = $this->request->get('filename');

            $directory = storage_path('app/sync');

            $file = $directory . '/' . $filename;

            if (file_exists($file)) {
                if (strpos($filename, 'import0_1.xml') !== false) {
                    ProcessImportProduct1C::dispatch($file);

                    return $this->answer('success');
                } elseif (strpos($filename, 'offers0_1.xml') !== false) {
                    ProcessOffersProduct1C::dispatch($file);

                    return $this->answer('success');
                } elseif (Str::startsWith($filename, 'orders-')) {
                    ImportOrders::dispatch($filename);

                    return $this->answer('success');
                } else {
                    Log::error('didn`t find xml file');

                    return $this->failure('didn`t find xml file');
                }
            }

            return $this->answer('success');
        } catch (\Throwable $th) {
            Log::error('step parsing');
            Log::error($th);

            return $this->failure('Error on step parsing');
        }
    }

    protected function processQuery()
    {
        try {
            $orders = Order::where('sent_to_1c', 0)->with('items', 'items.product1c', 'user')->get();

            foreach ($orders as $order) {
                $order->update([
                    'sent_to_1c' => 1,
                ]);
                Log::info('Export order: ' . $order->order_number);
            }

            return response()->view('export.orders', compact('orders'))->header('Content-Type', 'application/xml');
        } catch (\Throwable $th) {
            Log::error('Error when synced of orders');
            Log::error($th);

            return $this->failure('Error when synced of orders');
        }
    }
}
