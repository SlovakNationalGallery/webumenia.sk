<?php

class ArticleController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		if (Entrust::hasRole('admin')) {
			$articles = Article::orderBy('created_at', 'desc')->paginate(20);
		} else {
			// $articles = Article::where('user_id', '=', Auth::user()->id)->orderBy('created_at', 'desc')->paginate(20);
		}
		// $articles = Item::orderBy('created_at', 'DESC')->get();
        return View::make('articles.index')->with('articles', $articles);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
        return View::make('articles.form');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$input = Input::all();

		$rules = Article::$rules;
		$v = Validator::make($input, $rules);

		if ($v->passes()) {
			
			$article = new Article;
			$article->author = Input::get('author');
			$article->title = Input::get('title');
			$article->slug = Input::get('slug');
			if (Input::has('category_id')) {
				$article->category_id = Input::get('category_id');
			}
			$article->summary = Input::get('summary');
			$article->content = Input::get('content');
			$article->publish = Input::get('publish', false);
			$article->promote = Input::get('promote', false);
			if (Input::has('title_color')) {
				$article->title_color = Input::get('title_color');
			}
			if (Input::has('title_shadow')) {
				$article->title_shadow = Input::get('title_shadow');
			}
			
			$article->save();

			if (Input::hasFile('main_image')) {
				$this->uploadMainImage($article);
			}

			return Redirect::route('article.index');
		}

		return Redirect::back()->withInput()->withErrors($v);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$article = Article::find($id);
        return View::make('articles.show')->with('article', $article);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$article = Article::find($id);

		if(is_null($article))
		{
			return Redirect::route('article.index');
		}

        return View::make('articles.form')->with('article', $article);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$v = Validator::make(Input::all(), Article::$rules);

		if($v->passes())
		{
			$input = array_except(Input::all(), array('_method'));

			$article = Article::find($id);
			$article->author = Input::get('author');
			$article->title = Input::get('title');
			$article->slug = Input::get('slug');
			if (Input::has('category_id')) {
				$article->category_id = Input::get('category_id');
			}
			$article->summary = Input::get('summary');
			$article->content = Input::get('content');
			$article->publish = Input::get('publish', false);
			$article->promote = Input::get('promote', false);
			if (Input::has('title_color')) {
				$article->title_color = Input::get('title_color');
			}
			if (Input::has('title_shadow')) {
				$article->title_shadow = Input::get('title_shadow');
			}

			$article->save();

			if (Input::hasFile('main_image')) {
				$this->uploadMainImage($article);
			}

			Session::flash('message', 'Článok <code>'.$article->title.'</code> bol upravený');
			return Redirect::route('article.index');
		}

		return Redirect::back()->withErrors($v);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		Article::find($id)->delete();
		return Redirect::route('article.index')->with('message', 'Článok bol zmazaný');;
	}


	private function uploadMainImage($article) {
		$main_image = Input::file('main_image');
		$uploaded_image = Image::make($main_image->getRealPath());
		$uploaded_image->widen(1200);
		$extension = $main_image->getClientOriginalExtension();
		$filename = md5(date("YmdHis").rand(5,50)) . "." . $extension;
		$article->main_image = $filename;
		$filename = $article->getHeaderImage(true);
		$uploaded_image->save($filename);
		$article->save();
	}

}