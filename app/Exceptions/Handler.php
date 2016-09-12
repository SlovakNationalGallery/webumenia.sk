<?php namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Item;

class Handler extends ExceptionHandler
{

    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        ValidationException::class,
        'Symfony\Component\HttpKernel\Exception\HttpException'
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $e
     * @return void
     */
    public function report(Exception $e)
    {
        parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        if ($e instanceof NotFoundHttpException) {

            $resolvedUrl = $this->resolveOldUrl($request);
            if ($resolvedUrl) {
                return redirect($resolvedUrl, 301);
            }

            $item = Item::random()->first();
            return response()->view('errors.missing', ['item' => $item], 404);
        }

        if (!config('app.debug') && app()->environment() == 'production') {
            return response()->view('errors.fatal', [], 500);
        }

        return parent::render($request, $e);
    }


    private function resolveOldUrl($request) {
        if ($request->is('cedvuweb/image/*')) {
            $id = $request->get('id');
            if (!empty($id)) {
                return Item::getImagePathForId($id);
            }
        } elseif ($request->is('web/guest/*') || $request->is('web/ogd/*') || $request->is('web/gmb/*') || $request->is('web/gnz/*'))
        {
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
            $uri = (!$params) ? $uri : $uri.'/'.$params;
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
                    $id_array = array_filter($parts, function($part) {
                      return fnmatch('SVK:*', $part);
                    });
                    $id = reset($id_array);
                    $item = Item::find($id);
                    if ($item){
                        return $item->getUrl();
                    }
                    break;
                
                case 'search':
                    $query = array_pop($parts);
                    $query = urldecode($query);
                    parse_str($query, $output);
                    // $query = preg_replace("/(\w+)[=]/", " ", $query); // vymaze slova konciace "=" alebo ":" -> napr "au:"
                    $query = (isSet($output['query'])) ? $output['query'] : '';
                    if (preg_match_all('/\s*([^:]+):(.*)/', $query, $matches)) {
                       $apply_filters = array();
                       $filters = array_combine ( $matches[1], $matches[2] );
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
                
                case (array_key_exists($action, $work_type_lookup)):
                    $work_type = $work_type_lookup[$action];
                    return url('katalog?work_type=' . $work_type);
                    break;
            }
        }

        return false;
    }
}
