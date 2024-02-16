<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class NewCatalogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return view('frontend.catalog-new.index-new', ['title' => self::generateTitle($request)]);
    }

    private static function generateTitle(Request $request)
    {
        $filters = $request->input('filter');

        $attributes = collect([
            'search',
            'author',
            'work_type',
            'tag',
            'gallery',
            'credit',
            'topic',
            'medium',
            'technique',
            'has_image',
            'has_iip',
            'is_free',
            'related_work',
            'date_earliest',
        ]);

        return $attributes
            ->filter(fn($attribute) => Arr::has($filters, $attribute))
            ->map(function ($attribute) use ($filters) {
                if ($attribute === 'date_earliest') {
                    $from = Arr::get($filters, 'date_latest.gte');
                    $to = Arr::get($filters, 'date_earliest.lte');

                    if (!$from || !$to) {
                        return;
                    }

                    return trans('item.filter.title_generator.years', [
                        'from' => $from,
                        'to' => $to,
                    ]);
                }

                $values = collect(
                    is_array($filters[$attribute]) ? $filters[$attribute] : [$filters[$attribute]]
                );

                if ($attribute === 'author') {
                    $values = $values->map(fn($v) => formatName($v));
                }

                return trans('item.filter.title_generator.' . $attribute, [
                    'value' => $values->implode(', '),
                ]);
            })
            ->filter() // filter out empty values
            ->implode(' • ');
    }
}
