<?php

namespace Tests;

use Laravel\Dusk\TestCase as BaseTestCase;
use Tests\Traits\RunsWebServer;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;

abstract class DuskTestCase extends BaseTestCase
{
    use CreatesApplication, RunsWebServer;

    /**
     * Prepare for Dusk test execution.
     *
     * @beforeClass
     * @return void
     */
    public static function prepare()
    {
        static::startWebServer();
        // static::startChromeDriver();
    }

    /**
     * Create the RemoteWebDriver instance.
     *
     * @return \Facebook\WebDriver\Remote\RemoteWebDriver
     */
    protected function driver()
    {
        return RemoteWebDriver::create(
            // 'http://localhost:9515', DesiredCapabilities::chrome()
            'http://0.0.0.0:4444/wd/hub', DesiredCapabilities::chrome()
        );
    }
}
