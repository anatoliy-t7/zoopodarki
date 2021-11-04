<?php
namespace App\Http\Livewire\Site\Search;

use App\Models\Product;
use Livewire\Component;
use App\Traits\Searcheable;
use MeiliSearch\Endpoints\Indexes as SearchIndexes;

class SearchCom extends Component
{
    use Searcheable;

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
            $resultArray = $this->searchThis($this->search, true);
            $this->result = $resultArray['result'];
            $this->search = $resultArray['search'];
        }

        return view('livewire.site.search.search-com');
    }
}
