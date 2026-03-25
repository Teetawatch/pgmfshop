<?php

namespace App\Livewire\Traits;

trait WithSeo
{
    public string $seoTitle = '';
    public string $seoDescription = '';
    public string $seoImage = '';
    public string $seoCanonical = '';
    public string $seoOgType = 'website';

    protected function setSeo(
        string $title = '',
        string $description = '',
        string $image = '',
        string $canonical = '',
        string $ogType = 'website'
    ): void {
        $this->seoTitle = $title;
        $this->seoDescription = $description;
        $this->seoImage = $image;
        $this->seoCanonical = $canonical ?: url()->current();
        $this->seoOgType = $ogType;
    }

    protected function getSeoData(): array
    {
        return array_filter([
            'seoTitle' => $this->seoTitle,
            'seoDescription' => $this->seoDescription,
            'seoImage' => $this->seoImage,
            'seoCanonical' => $this->seoCanonical,
            'seoOgType' => $this->seoOgType,
        ]);
    }

    protected function renderWithSeo(string $view, array $data = [])
    {
        return view($view, $data)->layoutData($this->getSeoData());
    }
}
