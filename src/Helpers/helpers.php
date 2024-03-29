<?php


use Dashed\DashedCore\CMSManager;
use Dashed\DashedCore\SeoManager;

if (! function_exists('cms')) {
    function cms(): CMSManager
    {
        return app(CMSManager::class);
    }
}

if (! function_exists('frontend')) {
    function seo(): SeoManager
    {
        return app(SeoManager::class);
    }
}
