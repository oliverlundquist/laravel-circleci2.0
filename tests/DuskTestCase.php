<?php

namespace Tests;

use Laravel\Dusk\TestCase as BaseTestCase;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Tests\Traits\RunsBrowserStackLocal;

abstract class DuskTestCase extends BaseTestCase
{
    use CreatesApplication, RunsBrowserStackLocal;

    /**
     * Prepare for Dusk test execution.
     *
     * @beforeClass
     * @return void
     */
    public static function prepare()
    {
        // static::startChromeDriver();
    }

    /**
     * Create the RemoteWebDriver instance.
     *
     * @return \Facebook\WebDriver\Remote\RemoteWebDriver
     */
    protected function driver()
    {
        // IE
        if (env('TEST_BROWSER') === 'IE') {
            $url = 'https://' . env('BROWSERSTACK_USERNAME') . ':' . env('BROWSERSTACK_ACCESS_KEY') . '@' . env('BROWSERSTACK_SERVER') . '/wd/hub';
            $capabilities = $this->browserStackCaps();

            static::startBrowserStackLocal();
            return RemoteWebDriver::create($url, $capabilities);
        }

        // Firefox
        if (env('CIRCLECI') === true && env('TEST_BROWSER') === 'firefox') {
            $url = 'http://0.0.0.0:4444/wd/hub';
            $capabilities = DesiredCapabilities::firefox();

            return RemoteWebDriver::create($url, $capabilities);
        }

        // Chrome
        if (env('CIRCLECI') === true && env('TEST_BROWSER') === 'chrome') {
            $url = 'http://0.0.0.0:4444/wd/hub';
            $capabilities = DesiredCapabilities::chrome();

            return RemoteWebDriver::create($url, $capabilities);
        }

        // Chrome (local)
        $url = 'http://localhost:9515';
        $capabilities = DesiredCapabilities::chrome();
        return RemoteWebDriver::create($url, $capabilities);
    }

    /**
     * Setup the BrowserStack capabilities
     *
     * @see https://www.browserstack.com/automate/capabilities
     * @return array
     */
    private function browserStackCaps()
    {
        $caps = [
            'project'              => config('app.name'),
            'browserstack.local'   => true,
            'browserstack.console' => 'info',
            // 'browserstack.debug'   => true,
            'os'                   => 'Windows',
            'os_version'           => '10',
            'browser'              => 'IE',
            'browser_version'      => '15',
            'resolution'           => '1024x768'
        ];

        return $caps;
    }

    /**
     * Disable storeConsoleLogsFor for BrowerStack because getLogs seems to be unsupported by many browsers
     *
     * @param  \Illuminate\Support\Collection $browsers
     * @return void
     */
    protected function storeConsoleLogsFor($browsers)
    {
        return;
    }
}
