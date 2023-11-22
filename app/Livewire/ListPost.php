<?php

namespace App\Livewire;

use Livewire\Component;

class ListPost extends Component
{

    public string $industryActive = 'All';
    public array $industries = [
        'All',
        'IT',
        'Finance',
        'Marketing',
        'Sales',
        'HR',
        'Others',
    ];
    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        $listPost = \App\Models\Post::query()->where('end_date', '>=', now());
        $industryActive = $this->industryActive;

        if ($this->industryActive !== 'All') {
            $listPost->where('industry', $this->industryActive);
        }

        $listPost = $listPost->get();
        return view('livewire.list-post', compact('listPost', 'industryActive'));
    }

    public function filterIndustry(string $industry = ''): void
    {
        $this->industryActive = $industry;
    }
}
