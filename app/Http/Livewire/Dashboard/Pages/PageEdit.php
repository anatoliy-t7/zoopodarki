<?php

namespace App\Http\Livewire\Dashboard\Pages;

use App\Models\Page;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
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
    public $template = 'plain';
    public $content = [''];
    public $isActive = false;
    public $newFiles = [];
    protected $listeners = [
        'save',
        'editorjs-save:editorId' => 'saveEditorState',
    ];
    protected $queryString = ['pageId'];

    public function mount()
    {
        if (request()->has('pageId')) {
            $page = Page::where('id', request()->pageId)->first();
            $this->pageId = $page->id;
            $this->title = $page->title;
            $this->meta_title = $page->meta_title;
            $this->meta_description = $page->meta_description;
            $this->slug = $page->slug;
            $this->content = json_decode($page->content);
            $this->isActive = $page->isActive;
        }
    }

    public function saveEditorState($editorJsonData)
    {
        dd($editorJsonData);
        $this->content = $editorJsonData;
    }

    public function addBlock()
    {
        array_push($this->content, '');
    }

    public function completeUpload($uploadedUrl, $eventName)
    {
        foreach ($this->newFiles as $file) {
            if ($file->getFilename() === $uploadedUrl) {
                $newFileName = $file->store('/', 'public');

                $url = Storage::disk('public')->url($newFileName);
                $this->dispatchBrowserEvent($eventName, [
                    'url' => $url,
                    'href' => $url,
                ]);

                return;
            }
        }
    }

    public function removeFileAttachment($url)
    {
        try {
            Storage::disk('public')->delete($url);

            toast()
                ->success('Изображение удалено')
                ->push();
        } catch (\Throwable$th) {
            toast()
                ->warning('Изображение не удалено')
                ->push();
        }
    }

    public function save()
    {
        dd($this->content);
        $this->validate([
            'title' => 'required|unique:pages,title,' . $this->pageId,
            'slug' => 'required',
        ]);

        // if (Str::of($this->content)->exactly('<p><br></p>')) {
        //     $this->content = null;
        // }

        DB::transaction(function () {
            $page = Page::updateOrCreate(
                ['id' => $this->pageId],
                [
                    'title' => trim($this->title),
                    'meta_title' => trim($this->meta_title),
                    'meta_description' => trim($this->meta_description),
                    'slug' => $this->slug,
                    'content' => json_encode($this->content),
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
