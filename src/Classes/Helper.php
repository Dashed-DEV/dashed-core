<?php

namespace Dashed\DashedCore\Classes;

use Illuminate\Support\Facades\Request;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class Helper
{
    public static function urlIsActive($url, $exact = false)
    {
        $url = url($url);
        if ($url == Request::url() || url(str_replace(url('/'), '', $url)) == Request::url()) {
            return true;
        }

        if ($exact) {
            return false;
        }

        if (strpos(Request::url(), $url) !== false) {
            return true;
        }

        return false;
    }

    public static function getProfilePicture($email)
    {
        return 'https://www.gravatar.com/avatar/' . md5(strtolower(trim($email)));
    }

    public static function getAdminUrl()
    {
        return url(config('filament.path'));
    }

    public static function getLocalUrl($url)
    {
        return LaravelLocalization::localizeUrl($url);
    }

    public static function getCurrentUrlInLocale($locale, $url = null)
    {
        if (! $url) {
            $url = '/' . $locale;
        }

        return $url;
        //When using below, it removes the defaultLocale from URL, which makes it impossible to switch
        return LaravelLocalization::getLocalizedURL($locale, $url);
    }
}
