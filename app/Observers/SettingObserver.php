<?php

namespace App\Observers;

use App\Models\Setting;
use Illuminate\Support\Facades\Storage;

class SettingObserver
{
    public function updated(Setting $setting): void
    {
        if ($setting->isDirty('logo') && $setting->getOriginal('logo')) {
            Storage::disk('public')->delete($setting->getOriginal('logo'));
        }
    }

    public function deleted(Setting $setting): void
    {
        if (! is_null($setting->logo)) {
            Storage::disk('public')->delete($setting->logo);
        }
    }
}
