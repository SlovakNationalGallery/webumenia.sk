<?php

namespace App\Http\Controllers;

use App\Article;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\App;


class ClanokController extends Controller
{

    public function getIndex()
    {
        $articles = Article::published()->orderBy('published_date', 'desc');
        if (Input::has('author')) {
            $articles = $articles->where('author', 'LIKE', Input::get('author'));
        }
        $articles = $articles->get();
        return view('clanky', array('articles'=>$articles));
    }

    public function getSuggestions()
    {
        $q = (Input::has('search')) ? str_to_alphanumeric(Input::get('search')) : 'null';

        $result = Article::published()->where('title', 'like', '%'.$q.'%')->limit(5)->get();

        $data = array();
        $data['results'] = array();
        $data['count'] = 0;
        
        // $data['items'] = array();
        foreach ($result as $key => $hit) {

            $data['count']++;
            $params = array(
                'title' => $hit->title,
                'author' => $hit->author,
                'url' => $hit->getUrl(),
                'image' => $hit->getResizedImage(70),
            );
            $data['results'][] = array_merge($params) ;
        }

        return Response::json($data);
    }

    public function getDetail($slug)
    {
        // dd($slug);
        $article = Article::where('slug', '=', $slug)->firstOrFail();
        if (empty($article)) {
            App::abort(404);
        }
        $article->view_count += 1;
        $article->save();

        return view('clanok', array('article'=>$article));

    }
}
