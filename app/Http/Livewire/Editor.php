<?php

namespace App\Http\Livewire;

use Maxeckel\LivewireEditorjs\Http\Livewire\EditorJS;

class Editor extends EditorJS
{
    public function save()
    {
        $this->emitUp('editorjs-save', ['editorId' => $this->editorId, 'data' => $this->data]);
    }
}
