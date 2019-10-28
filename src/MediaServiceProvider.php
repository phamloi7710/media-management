<?php

namespace LoiPham\Media;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Broadcast;
use LoiPham\Media\Commands\PackageSetup;

class MediaServiceProvider extends ServiceProvider
{
    protected $file;

    public function boot()
    {
        $this->file = $this->app['files'];
        $this->load();
        $this->viewComp();
        $this->command();
    }

    /**
     * publish package assets.
     *
     * @return [type] [description]
     */
    protected function load()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/media.php', 'media');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'media');
        $this->loadRoutesFrom(__DIR__.'/Routes/web.php', 'media');
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations', 'media');
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'media');
    }


    /**
     * share data with view.
     *
     * @return [type] [description]
     */
    protected function viewComp()
    {
        $data = [];

        // base url
        $url    = $this->app['filesystem']
                    ->disk(config('media.storage_disk'))
                    ->url('/');

        $data['base_url'] = preg_replace('/\/+$/', '/', $url);

        // upload panel bg patterns
        $pattern_path = public_path('app-assets/media/patterns');

        if ($this->file->exists($pattern_path)) {
            $patterns = collect(
                $this->file->allFiles($pattern_path)
            )->map(function ($item) {
                $name = str_replace('\\', '/', $item->getPathName());

                return preg_replace('/.*\/patterns/', '/app-assets/media/patterns', $name);
            });

            $data['patterns'] = json_encode($patterns);
        }

        // share
        view()->composer('media::_manager', function ($view) use ($data) {
            $view->with($data);
        });
    }

    /**
     * package commands.
     *
     * @return [type] [description]
     */
    protected function command()
    {
        $this->commands([
            PackageSetup::class,
        ]);
    }

    /**
     * extra functionality.
     *
     * @return [type] [description]
     */
    public function register()
    {
        //
    }
}
