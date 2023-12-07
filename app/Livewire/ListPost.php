<?php

namespace App\Livewire;

use Livewire\Component;

class ListPost extends Component
{

    public int|null $industryIdActive = null;

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        $listPost = \App\Models\Post::query()->where('end_date', '>=', now())->where('published', true);
        $industryIdActive = $this->industryIdActive;

        if ($this->industryIdActive) {
            $listPost->where('industry_id', $this->industryIdActive);
        }

        $industries = \App\Models\Industry::all();

        $listPost = $listPost->get();
        return view('livewire.list-post', compact('listPost', 'industryIdActive', 'industries'));
    }

    public function filterIndustry(int|null $industryId = null): void
    {
        $this->industryIdActive = $industryId;
    }
}
