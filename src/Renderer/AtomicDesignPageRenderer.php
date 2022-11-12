<?php

namespace Beier\FilamentPages\Renderer;

use Beier\FilamentPages\Contracts\Renderer;
use Beier\FilamentPages\Exceptions\AtomicDesignPageRendererException;
use Beier\FilamentPages\Models\FilamentPage;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class AtomicDesignPageRenderer implements Renderer
{
    public function render(FilamentPage $filamentPage): View
    {
        $template = $this->computeTemplateName($filamentPage);

        $components = $this->getComponents($filamentPage);

        return view($template, compact('components'));
    }

    private function computeTemplateName(FilamentPage $filamentPage): string
    {
        $data = $filamentPage->data;

        if (! isset($data['templateName']) || blank($data['templateName'])) {
            throw new AtomicDesignPageRendererException(
                message: 'Key templateName is not set in data.'
            );
        }

        $template = str_replace('_template', '', $data['templateName']);

        return "components.templates.{$template}";
    }

    /**
     * @param  array  $data
     * @return array
     */
    private function getComponents(FilamentPage $filamentPage): array
    {
        $data = $filamentPage->data;

        if (! isset($data['content']) || blank($data['content'])) {
            throw new AtomicDesignPageRendererException(
                message: sprintf('key "%s" does not exist in given data.', 'content')
            );
        }

        $components = [];

        foreach ($data['content'] as $key => $component) {
            $components[$key] = $this->createComponent(name: $key, content: $component);
        }

        return $components;
    }

    private function createComponent(string $name, $content): Component
    {
        return new class($name, $content) extends Component
        {
            public function __construct(public readonly string $name, public readonly mixed $content)
            {
            }

            public function render(): View
            {
                return view("components.organisms.{$this->name}", ['content' => $this->content]);
            }
        };
    }
}
