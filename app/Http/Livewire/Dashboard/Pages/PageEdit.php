<?php
namespace App\Http\Livewire\Dashboard\Pages;

use App\Models\Page;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;

class PageEdit extends Component
{
    use WithFileUploads;

    public $pageId = null;
    public $title;
    public $meta_title;
    public $meta_description;
    public $slug;
    public $content;
    public $isActive = false;
    public $newFiles = [];
    protected $listeners = ['save'];
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
            $this->content = $page->content;
            $this->isActive = $page->isActive;
        }
    }

    public function completeUpload($uploadedUrl, $eventName)
    {
        foreach ($this->newFiles as $file) {
            if ($file->getFilename() === $uploadedUrl) {
                $newFileName = $file->store('/', 'content-attachments');

                $url = Storage::disk('content-attachments')->url($newFileName);
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
            Storage::disk('content-attachments')->delete($url);

            $this->dispatchBrowserEvent('toaster', ['message' => 'Изображение удалено']);
        } catch (\Throwable $th) {
            $this->dispatchBrowserEvent('toaster', ['class' => 'bg-red-500', 'message' => 'Изображение не удалено']);
        }
    }

    public function save()
    {
        $this->validate([
            'title' => 'required|unique:pages,title,' . $this->pageId,
            'slug' => 'required',
        ]);

        if (Str::of($this->content)->exactly('<p><br></p>')) {
            $this->content = null;
        }

        DB::transaction(function () {
            $page = Page::updateOrCreate(
                ['id' => $this->pageId],
                [
                    'title' => trim($this->title),
                    'meta_title' => trim($this->meta_title),
                    'meta_description' => trim($this->meta_description),
                    'slug' => $this->slug,
                    'content' => $this->content,
                    'isActive' => $this->isActive,
                ]
            );

            $this->pageId = $page->id;

            $this->dispatchBrowserEvent('toaster', ['message' => $page->title . ' сохранена.']);
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
