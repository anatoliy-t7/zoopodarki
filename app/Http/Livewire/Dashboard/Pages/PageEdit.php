<?php

namespace App\Http\Livewire\Dashboard\Pages;

use App\Models\Page;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;
use Usernotnull\Toast\Concerns\WireToast;

class PageEdit extends Component
{
    use WireToast;
    use WithFileUploads;

    public $pageId = null;
    public $title;
    public $meta_title;
    public $meta_description;
    public $slug;
    public $pageTemplate = 'plain';
    public $editor = [
        [
            'title' => '',
            'content' => '',
        ],
    ];
    public $isActive = false;
    public $newFiles = [];
    public $templates = [];
    protected $listeners = [
        'save',
        'editorjs-save' => 'saveDataEditor',
    ];
    protected $queryString = ['pageId'];
    protected $rules = [
        'editor.*.title' => 'required|string|min:3',
        'editor.*.content' => 'required|string',
        'pageTemplate' => 'required',
    ];

    public function mount()
    {
        if (request()->has('pageId')) {
            $page = Page::where('id', request()->pageId)->firstOrFail();
            $this->pageId = $page->id;
            $this->title = $page->title;
            $this->meta_title = $page->meta_title;
            $this->meta_description = $page->meta_description;
            $this->pageTemplate = $page->template;
            $this->slug = $page->slug;
            $this->isActive = $page->isActive;
            if (!empty($page->content) || $page->content != '') {
                $this->editor = json_decode($page->content, true);
                // dd($this->editor);
            }
        }
        $this->getListOfTemplates();
    }

    public function getListOfTemplates()
    {
        $this->templates = array_diff(scandir('../resources/views/site/pages/templates/'), ['..', '.']);

        foreach ($this->templates as $key => $template) {
            $this->templates[$key] = Str::before($template, '.');
        }
    }

    public function addBlockOfEditor()
    {
        array_push($this->editor, [
            'title' => '',
            'content' => '',
        ]);
    }

    public function removeBlockOfEditor($index)
    {
        unset($this->editor[$index]);
    }

    public function saveDataEditor($editorJsonData)
    {
        $index = Str::after($editorJsonData['editorId'], 'editor');
        $this->editor[$index]['content'] = json_encode($editorJsonData['data']);
    }

    public function save()
    {
        // dd($this->editor);
        $this->validate([
            'title' => 'required|unique:pages,title,' . $this->pageId,
            'slug' => 'required',
        ]);

        DB::transaction(function () {
            $page = Page::updateOrCreate(
                ['id' => $this->pageId],
                [
                    'title' => trim($this->title),
                    'meta_title' => trim($this->meta_title),
                    'meta_description' => trim($this->meta_description),
                    'slug' => $this->slug,
                    'template' => $this->pageTemplate,
                    'content' => json_encode($this->editor),
                    'isActive' => $this->isActive,
                ]
            );

            $this->pageId = $page->id;
            toast()
                ->success($page->title . ' сохранена.')
                ->push();
        });
    }

    public function remove()
    {
        Page::find($this->pageId)->delete();

        redirect()->route('dashboard.pages');
    }

    public function render()
    {
        return view('livewire.dashboard.pages.page-edit')
            ->extends('dashboard.app')
            ->section('content');
    }
}
