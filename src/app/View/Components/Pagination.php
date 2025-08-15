<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Pagination extends Component
{
    public $page;
    public $pages;
    public $baseUrl;
    /**
     * Create a new component instance.
     */
    public function __construct($page, $pages, $baseUrl)
    {
        $this->page = $page;
        $this->pages = $pages;
        $this->baseUrl = $baseUrl;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.pagination');
    }
}
