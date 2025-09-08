<?php

namespace App\Livewire\Store;

use Livewire\Component;

class MediaHeading extends Component
{

    public $slug;
    public $path;
    public $render;
    public $file_type;
    public $last;
    public $total_files;

    public function mount($slug, $path,$file_type, $last = false, $total_files = 0)
    {
        $this->slug = $slug;
        $this->path = $path;
        $this->file_type = $file_type;
        $this->last = $last;
        $this->total_files= $total_files;
    }

    public function render()
    {
       return view('components.store.media-heading');
    }
}
