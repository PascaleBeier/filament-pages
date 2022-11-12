<?php

namespace Beier\FilamentPages\Contracts;

interface FilamentPageTemplate
{
    public static function title(): string;

    public static function schema(): array;
}
