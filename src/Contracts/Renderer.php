<?php

namespace Beier\FilamentPages\Contracts;

use Beier\FilamentPages\Models\FilamentPage;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Response;

interface Renderer
{
    public function render(FilamentPage $filamentPage): Response|View;
}
