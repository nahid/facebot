<?php
namespace Nahid\FaceBot;
use Illuminate\Container\Container;
use Illuminate\Foundation\Application as LaravelApplication;
use Illuminate\Support\ServiceProvider;
use Laravel\Lumen\Application as LumenApplication;
use Nahid\FaceBot\Env\EnvManager;
use Nahid\FaceBot\Messengers\Message;


class FacebotServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->setupConfig();
    }
    /**
     * Register the application services.
     */
    public function register()
    {
        $this->registerEnvManager();
        $this->registerFacebot();
    }
    /**
     * Setup the config.
     */
    protected function setupConfig()
    {
       /* $source = realpath(__DIR__.'/../config/talk.php');
        // Check if the application is a Laravel OR Lumen instance to properly merge the configuration file.
        if ($this->app instanceof LaravelApplication && $this->app->runningInConsole()) {
            $this->publishes([$source => config_path('talk.php')]);
        } elseif ($this->app instanceof LumenApplication) {
            $this->app->configure('talk');
        }
        $this->mergeConfigFrom($source, 'talk');*/
    }

    /**
     * Register Message class.
     */
    protected function registerFacebot()
    {
        $this->app->singleton('facebot', function (Container $app) {
            return new Message();
        });

        $this->app->alias('facebot', Message::class);
    }
    /**
     * Register EnvManager class.
     */
    protected function registerEnvManager()
    {
        $this->app->singleton('facebot.env', function (Container $app) {
            return new EnvManager($app['facebot']);
        });

        $this->app->alias('facebot.env', EnvManager::class);
    }
    /**
     * Get the services provided by the provider.
     *
     * @return string[]
     */
    public function provides()
    {
        return [
            'facebot',
            'facebot.env',
        ];
    }
}