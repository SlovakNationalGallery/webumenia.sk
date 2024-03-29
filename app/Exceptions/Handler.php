<?php namespace App\Exceptions;

use App\Elasticsearch\Repositories\ItemRepository;
use App\Filter\ItemFilter;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Item;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        AuthorizationException::class,
        ValidationException::class,
        'Symfony\Component\HttpKernel\Exception\HttpException',
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = ['current_password', 'password', 'password_confirmation'];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            if (app()->bound('sentry')) {
                app('sentry')->captureException($e);
            }
        });
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Throwable $e)
    {
        if ($e instanceof NotFoundHttpException) {
            $resolvedUrl = $this->resolveOldUrl($request);
            if ($resolvedUrl) {
                return redirect($resolvedUrl, 301);
            }

            $filter = (new ItemFilter())->setHasImage(true)->setHasIip(true);
            $item = app(ItemRepository::class)
                ->getRandom(1, $filter)
                ->getCollection()
                ->first();
            return response()->view('errors.missing', ['item' => $item], 404);
        }

        if (!config('app.debug') && app()->environment() == 'production') {
            return response()->view('errors.fatal', [], 500);
        }

        return parent::render($request, $e);
    }

    private function resolveOldUrl($request)
    {
        if ($request->is('cedvuweb/image/*')) {
            $id = $request->get('id');
            if (!empty($id)) {
                return Item::getImagePathForId($id);
            }
        } elseif (
            $request->is('web/guest/*') ||
            $request->is('web/ogd/*') ||
            $request->is('web/gmb/*') ||
            $request->is('web/gnz/*')
        ) {
            $filter_lookup = [
                'author' => 'au',
                'work_type' => 'wt',
                'topic' => 'to',
            ];
            $work_type_lookup = [
                'photo' => 'fotografia',
                'graphic' => 'grafika',
                'drawing' => 'kresba',
                'painting' => 'maliarstvo',
                'sculpture' => 'sochárstvo',
                'graphic_design' => 'úžitkové umenie',
                'aplplied_arts' => 'úžitkové umenie',
                'ine_media' => 'iné médiá',
                'umelecke_remeslo' => 'umelecké remeslo',
            ];
            $uri = $request->path();
            $params = http_build_query($request->all());
            $uri = !$params ? $uri : $uri . '/' . $params;
            $uri = str_replace('results?', 'results/', $uri);
            $parts = explode('/', $uri);
            $action = $parts[2];
            switch ($action) {
                case 'home':
                    return '/';
                    break;

                case 'about':
                case 'contact':
                case 'help':
                    return 'informacie';
                    break;

                case 'detail':
                    $id_array = array_filter($parts, function ($part) {
                        return fnmatch('SVK:*', $part);
                    });
                    $id = reset($id_array);
                    $item = Item::find($id);
                    if ($item) {
                        return $item->getUrl();
                    }
                    break;

                case 'search':
                    $query = array_pop($parts);
                    $query = urldecode($query);
                    parse_str($query, $output);
                    // $query = preg_replace("/(\w+)[=]/", " ", $query); // vymaze slova konciace "=" alebo ":" -> napr "au:"
                    $query = isset($output['query']) ? $output['query'] : '';
                    if (preg_match_all('/\s*([^:]+):(.*)/', $query, $matches)) {
                        $apply_filters = [];
                        $filters = array_combine($matches[1], $matches[2]);
                        foreach ($filters as $filter => $value) {
                            if (in_array($filter, $filter_lookup)) {
                                $filter = array_search($filter, $filter_lookup);
                                switch ($filter) {
                                    case 'work_type':
                                        $parts = explode(', ', $value);
                                        $value = reset($parts);
                                        $value = str_to_alphanumeric($value);
                                        $apply_filters[$filter] = $value;
                                        break;
                                    case 'author':
                                        $replace_pairs = ['"' => '', '\"' => '', '“' => ''];
                                        $value = strtr($value, $replace_pairs);
                                        $parts = explode(' ', $value);
                                        if (count($parts) > 1) {
                                            $last_name = array_pop($parts);
                                            $value = $last_name . ', ' . implode(' ', $parts);
                                            $apply_filters[$filter] = $value;
                                        }
                                        break;
                                    default:
                                        $replace_pairs = ['"' => '', '\"' => '', '“' => ''];
                                        $value = strtr($value, $replace_pairs);
                                        $apply_filters[$filter] = $value;
                                        break;
                                }
                            }
                        }
                        if (!empty($apply_filters)) {
                            return 'katalog?' . http_build_query($apply_filters);
                        }
                        $query = implode(' ', $filters);
                    }
                    $query = $value = str_to_alphanumeric($query, ' ');
                    return 'katalog?search=' . urlencode($query);
                    break;

                case array_key_exists($action, $work_type_lookup):
                    $work_type = $work_type_lookup[$action];
                    return url('katalog?work_type=' . $work_type);
                    break;
            }
        }

        return false;
    }

    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        return redirect()->guest('login');
    }
}
