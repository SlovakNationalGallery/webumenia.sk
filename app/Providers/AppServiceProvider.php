<?php namespace App\Providers;

use App\Elasticsearch\Repositories\AuthorityRepository;
use App\Elasticsearch\Repositories\ItemRepository;
use App\Enums\FrontendEnum;
use App\Filter\Forms\Types\AuthoritySearchRequestType;
use App\Filter\Forms\Types\ItemSearchRequestType;
use App\Harvest\Importers\ItemImporter;
use App\Harvest\Mappers\BaseAuthorityMapper;
use App\Services\Frontend;
use DrewM\MailChimp\MailChimp;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;
use League\Flysystem\Filesystem;
use League\Flysystem\WebDAV\WebDAVAdapter;
use Sabre\DAV\Client;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * This service provider is a great spot to register your various container
     * bindings with the application. As you can see, we are registering our
     * "Registrar" implementation here. You can add your own bindings too!
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(AuthorityRepository::class);
        $this->app->singleton(ItemRepository::class);
        $this->app->bind(AuthoritySearchRequestType::class);
        $this->app->bind(ItemSearchRequestType::class);

        $this->app
            ->when(AuthorityRepository::class)
            ->needs('$locales')
            ->give(config('translatable.locales'));
        $this->app
            ->when(ItemRepository::class)
            ->needs('$locales')
            ->give(config('translatable.locales'));

        $this->app
            ->when(ItemImporter::class)
            ->needs(BaseAuthorityMapper::class)
            ->give(function () {
                return new BaseAuthorityMapper();
            });

        $this->app->singleton(Frontend::class, fn() => new Frontend(FrontendEnum::WEBUMENIA));
        $this->app->bind(MailChimp::class, fn() => new MailChimp(config('mailchimp.apiKey')));
    }

    public function boot()
    {
        Paginator::useBootstrap();

        Blade::directive('date', function ($expression) {
            return $this->formatDate($expression, 'LL');
        });
        Blade::directive('dateShort', function ($expression) {
            return $this->formatDate($expression, 'L');
        });
        Blade::directive('datetime', function ($expression) {
            return $this->formatDate($expression, 'L LT');
        });

        Storage::extend('webdav', function ($app, $config) {
            $client = new Client([
                'baseUri' => $config['base_uri'],
                'userName' => $config['username'],
                'password' => $config['password'],
            ]);
            $adapter = new WebDAVAdapter($client, $config['prefix'] ?? '');
            $driver = new Filesystem($adapter);
            return new FilesystemAdapter($driver, $adapter, $config);
        });
    }

    private function formatDate($expression, $format)
    {
        return $expression
            ? "<?php echo \Carbon\Carbon::parse($expression)->locale(App::getLocale())->isoFormat('$format'); ?>"
            : '';
    }
}
