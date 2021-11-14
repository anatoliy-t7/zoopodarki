<?php
namespace App\Http\Livewire\Site\Search;

use App\Traits\Searcheable;
use Livewire\Component;
use Usernotnull\Toast\Concerns\WireToast;

class SearchCom extends Component
{
    use Searcheable;
    use WireToast;

    public $search;
    public $result = [];
    protected $listeners = ['resetSearch'];

    public function resetSearch()
    {
        $this->reset();
    }

    public function render()
    {
        if ($this->search) {
            try {
                $resultArray = $this->searchThis($this->search, true);
                $this->result = $resultArray['result'];
                $this->search = $resultArray['search'];
            } catch (\Throwable$th) {
                \Log::error('Ошибка поиска');
                \Log::error($th);

                toast()
                    ->warning('Поиск временно не работает, мы уже работаем над этим')
                    ->push();

            }
        }

        return view('livewire.site.search.search-com');
    }
}
