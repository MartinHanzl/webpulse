<?php

namespace App\Http\Controllers;

use App\Models\Site\Site;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\App;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function handleLanguage(?string $lang = null): string
    {
        if (! $lang) {
            $lang = App::getLocale();
        }
        App::setLocale($lang);

        return $lang;
    }

    public function handleSite(?string $hash = null): int
    {
        if (! $hash) {
            App::abort(404);
        }
        $site = Site::query()
            ->where('hash', $hash)
            ->where('is_active', true)
            ->first();

        if (! $site) {
            App::abort(404);
        }

        return $site->id;
    }
}
