<?php

namespace App\Livewire;

use Illuminate\Http\Request;
use Livewire\Component;

class PostSlug extends Component
{
    public function render(Request $request): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        $slug = $request->slug;
        $post = \App\Models\Post::query()->where('slug', $slug)->firstOrFail();

        return view('livewire.post-slug', compact('post'));
    }
}
