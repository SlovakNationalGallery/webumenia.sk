<?php namespace App\Providers;

use App\Authority;
use App\Elasticsearch\Repositories\AuthorityRepository;
use App\Elasticsearch\Repositories\ItemRepository;
use App\Filter\Forms\Types\AuthoritySearchRequestType;
use App\Filter\Forms\Types\ItemSearchRequestType;
use App\Filter\Generators\AuthorityTitleGenerator;
use App\Filter\Generators\ItemTitleGenerator;
use App\Harvest\Importers\ItemImporter;
use App\Harvest\Mappers\BaseAuthorityMapper;
use App\Item;
use App\Observers\AuthorityObserver;
use App\Observers\ItemObserver;
use Carbon\Carbon;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessor;

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
        $this->app->singleton(PropertyAccessor::class, function () {
            return PropertyAccess::createPropertyAccessor();
        });

        $this->app->singleton(AuthorityRepository::class);
        $this->app->singleton(ItemRepository::class);
        $this->app->singleton(AuthorityTitleGenerator::class);
        $this->app->singleton(ItemTitleGenerator::class);
        $this->app->bind(AuthoritySearchRequestType::class);
        $this->app->bind(ItemSearchRequestType::class);

        $this->app->when(AuthorityRepository::class)
            ->needs('$locales')
            ->give(config('translatable.locales'));
        $this->app->when(ItemRepository::class)
            ->needs('$locales')
            ->give(config('translatable.locales'));

        $this->app->when(ItemImporter::class)
            ->needs(BaseAuthorityMapper::class)
            ->give(function () {
                return new BaseAuthorityMapper();
            });
    }

    public function boot()
    {
        Authority::observe(AuthorityObserver::class);
        Item::observe(ItemObserver::class);
        
        
        Blade::directive('date', function ($expression) {
            return $this->formatDate($expression, 'LL');
        });
        Blade::directive('dateShort', function ($expression) {
            return $this->formatDate($expression, 'L');
        });
        Blade::directive('datetime', function ($expression) {
            return $this->formatDate($expression, 'L LT');
        });
    }


    private function formatDate($expression, $format)
    {
        return $expression? "<?php echo \Carbon\Carbon::parse($expression)->locale(App::getLocale())->isoFormat('$format'); ?>": "";
    }
}
