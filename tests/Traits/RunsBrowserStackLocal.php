<?php

namespace Tests\Traits;

trait RunsBrowserStackLocal
{
    /**
     * @var Process
     */
    protected static $bs_local;

    /**
     * Start BrowserStack Local
     */
    public static function startBrowserStackLocal()
    {
        $bs_local_args = array("key" => env('BROWSERSTACK_ACCESS_KEY'));
        static::$bs_local = new \BrowserStack\Local();
        static::$bs_local->start($bs_local_args);;
        static::afterClass(function() {
            if(static::$bs_local) static::$bs_local->stop();
        });
    }
}
