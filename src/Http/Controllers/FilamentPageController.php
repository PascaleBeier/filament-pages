<?php

namespace Beier\FilamentPages\Http\Controllers;

use Beier\FilamentPages\Contracts\Renderer;
use Beier\FilamentPages\Models\FilamentPage;
use Illuminate\Contracts\View\View;

class FilamentPageController
{
    public function __construct(private readonly Renderer $renderer)
    {
    }

    public function show(FilamentPage $filamentPage): View
    {
        return $this->renderer->render($filamentPage);
    }
}
