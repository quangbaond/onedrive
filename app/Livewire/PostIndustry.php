<?php

namespace App\Livewire;

use Illuminate\Http\Request;
use Livewire\Component;

class PostIndustry extends Component
{
    public function render(Request $request): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        $industry = $request->query('industry');
        $listPost = \App\Models\Post::query()->where('industry', $industry)->get();

        return view('livewire.post-industry', compact('listPost', 'industry'));
    }
}
