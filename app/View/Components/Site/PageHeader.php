<?php

namespace App\View\Components\Site;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class PageHeader extends Component
{
   public ?string $title;
    public ?string $subtitle;
    public array $breadcrumb;

    public function __construct(?string $title, ?string $subtitle = null, array $breadcrumb = [])
    {
        $this->title = $title;
        $this->subtitle = $subtitle;
        $this->breadcrumb = $breadcrumb;
    }

    public function render(): View|Closure|string
    {
        return view('components.site.page-header');
    }
}
