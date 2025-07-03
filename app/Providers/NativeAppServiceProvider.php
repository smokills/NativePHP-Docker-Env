<?php

namespace App\Providers;

use Native\Laravel\Facades\Window;
use Native\Laravel\Contracts\ProvidesPhpIni;
use Native\Laravel\Facades\Menu;

class NativeAppServiceProvider implements ProvidesPhpIni
{
    /**
     * Executed once the native application has been booted.
     * Use this method to open windows, register global shortcuts, etc.
     */
    public function boot(): void
    {
        Window::open()
            ->width(1000)
            ->height(700)
            ->resizable()
            ->title('ChefGPT');

        Menu::app([
            ['label' => 'File', 'submenu' => [
                ['id' => 'export-last', 'label' => 'Esporta ultima ricetta'],
                ['label' => 'Esci', 'role' => 'quit'],
            ]],
        ]);
    }

    /**
     * Return an array of php.ini directives to be set.
     */
    public function phpIni(): array
    {
        return [
        ];
    }
}
