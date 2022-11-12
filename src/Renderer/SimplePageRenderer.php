<?php

namespace Beier\FilamentPages\Renderer;

use Beier\FilamentPages\Contracts\Renderer;
use Beier\FilamentPages\Models\FilamentPage;
use Illuminate\Contracts\View\View;

class SimplePageRenderer implements Renderer
{
    public function render(FilamentPage $filamentPage): View
    {
        $layout = config('filament-pages.default_layout', 'layouts.app');

        return view($layout, ['page' => $filamentPage, 'data' => $filamentPage->data['content'] ?? []]);
    }
}
