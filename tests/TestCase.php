<?php

namespace Brackets\AdvancedLogger\Test;

use Brackets\AdvancedLogger\AdvancedLoggerServiceProvider;
use Carbon\Carbon;
use Illuminate\Support\Facades\Route;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    /**
     * Setup the test environment.
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
    }

    /**
     * Clean up the testing environment before the next test.
     *
     * @return void
     */
    protected function tearDown()
    {
//        if(file_exists($this->getRequestLogFileName())) {
//            unlink($this->getRequestLogFileName());
//        }
        parent::tearDown();
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     */
    protected function getEnvironmentSetUp($app)
    {
        if(file_exists($this->getRequestLogFileName())) {
            unlink($this->getRequestLogFileName());
        }
        $app['config']->set('advanced-logger.request.file', $this->getFixturesDirectory('requests.log'));
        $app['config']->set('advanced-logger.request.excluded-paths', ['excluded']);

        Route::get('/', function () {
            return 'Hi there.';
        });

        Route::get('/excluded', function () {
            return 'This is excluded path.';
        });
    }

    public function getFixturesDirectory(string $path): string
    {
        return __DIR__ . "/fixtures/{$path}";
    }

    public function getRequestLogFileName()
    {
        return $this->getFixturesDirectory('requests-' . Carbon::now()->format('Y-m-d') . '.log');
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            AdvancedLoggerServiceProvider::class,
        ];
    }
}