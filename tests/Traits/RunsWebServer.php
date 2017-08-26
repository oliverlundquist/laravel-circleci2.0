<?php

namespace Tests\Traits;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\ProcessBuilder;

trait RunsWebServer
{
    /**
     * @var Process
     */
    protected static $webServerProcess;

    /**
     * Start the web server process
     */
    public static function startWebServer()
    {
        static::$webServerProcess = static::buildServerProcess();
        static::$webServerProcess->start();
        static::afterClass(function() {
            static::stopWebServer();
        });
    }

    /**
     * Stop the web server process
     */
    public static function stopWebServer()
    {
        if (static::$webServerProcess) {
            static::$webServerProcess->stop();
        }
    }

    /**
     * Build the process to run the web server
     *
     * @return \Symfony\Component\Process\Process
     * @throws \Symfony\Component\Process\Exception\InvalidArgumentException
     * @throws \Symfony\Component\Process\Exception\LogicException
     */
    protected static function buildServerProcess()
    {
        $host = env('TEST_SERVER_HOST', '0.0.0.0');
        $port = env('TEST_SERVER_PORT', 9000);
        return (new ProcessBuilder())
            ->setTimeout(null)
            ->setWorkingDirectory(realpath(__DIR__.'/../'))
            ->add('exec')
            ->add(PHP_BINARY)
            ->add('artisan')
            ->add('serve')
            ->add("--port=$port")
            ->add("--env=testing")
            ->getProcess();
    }
}
