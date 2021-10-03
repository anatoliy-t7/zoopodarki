<?php
namespace App\Traits;

trait Delivery
{
    public function getDeliveryCosts($totalWeight, $zip)
    {
        $boxberryToken = config('constants.boxberry_token');

        if ($this->checkZip($zip, $boxberryToken)) {
            $url = 'http://api.boxberry.ru/json.php?token=' . $boxberryToken . '&method=DeliveryCosts&weight=' . $totalWeight . '&target=010&targetstart=010&zip=' . $zip;

            $handle = fopen($url, 'rb');
            $contents = stream_get_contents($handle);
            fclose($handle);
            $data = json_decode($contents, true);

            if (count($data) <= 0 || array_key_exists('err', $data)) {
                logger($data['err']);
            } else {
                logger($data);
            }
        }
    }

    public function checkZip($zip, $boxberryToken)
    {

        // TODO неработает, возможно заблокировали
        $url = 'https://api.boxberry.ru/json.php?token=' . $boxberryToken . '&method=ZipCheck&Zip=' . $zip;

        $handle = fopen($url, 'rb');
        $contents = stream_get_contents($handle);
        fclose($handle);
        $data = json_decode($contents, true);

        if (count($data) <= 0 || array_key_exists('err', $data)) {
            logger($data['err']);

            return false;
        } else {
            logger($data);

            return true;
        }
    }
}
