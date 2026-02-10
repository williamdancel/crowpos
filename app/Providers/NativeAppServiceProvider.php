<?php

namespace App\Providers;

use Native\Desktop\Facades\Window;

class NativeAppServiceProvider
{
    /**
     * Executed once the native application has been booted.
     * Use this method to open windows, register global shortcuts, etc.
     */
    public function boot(): void
    {
        // Open main window
        Window::open()
        ->title('CrowPOS')
        ->fullscreen()
        ->alwaysOnTop()
        ->resizable(true);
    }
}
