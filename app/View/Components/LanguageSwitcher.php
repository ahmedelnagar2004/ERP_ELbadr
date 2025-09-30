<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;

class LanguageSwitcher extends Component
{
    public $currentLocale;
    public $locales;
    public $routeName;
    public $routeParams;

    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        $this->currentLocale = App::getLocale();
        $this->locales = [
            'en' => 'English',
            'ar' => 'العربية',
        ];
        $this->routeName = Route::currentRouteName();
        $this->routeParams = request()->route() ? request()->route()->parameters() : [];
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.language-switcher', [
            'currentLocale' => $this->currentLocale,
            'locales' => $this->locales,
            'routeName' => $this->routeName,
            'routeParams' => $this->routeParams,
        ]);
    }
}
