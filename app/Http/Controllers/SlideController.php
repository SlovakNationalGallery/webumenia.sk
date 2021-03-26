<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Slide;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Intervention\Image\ImageManagerStatic;

class SlideController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        Carbon::setToStringFormat('d.m.Y H:i');

        $slides = Slide::orderBy('id', 'desc')->get();
        return view('slides.index')->with('slides', $slides);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('slides.form');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        $input = Request::all();

        $rules = Slide::$rules;
        $v = Validator::make($input, $rules);

        if ($v->passes()) {
            
            $input = Arr::except(Request::all(), array('_method'));

            $slide = new Slide;
            $slide->fill($input);
            $slide->save();

            if (Request::hasFile('image')) {
                $this->uploadImage($slide);
            }

            Session::flash('message', 'Carousel ' .$slide->name. ' bol vytvorený');

            return Redirect::route('slide.index');
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
        $slide = Slide::find($id);
        return view('slides.show')->with('slide', $slide);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $slide = Slide::find($id);

        if (is_null($slide)) {
            return Redirect::route('slide.index');
        }

        return view('slides.form')->with('slide', $slide);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        $v = Validator::make(Request::all(), Slide::$rules);
        // dd(Request::all());
        if ($v->passes()) {
            $input = Arr::except(Request::all(), array('_method'));

            $slide = Slide::find($id);
            $slide->fill($input);
            $slide->save();

            if (Request::hasFile('image')) {
                $this->uploadImage($slide);
            }

            Session::flash('message', 'Carousel ' .$slide->name. ' bol upravený');
            return Redirect::route('slide.index');
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
        Slide::find($id)->delete();
        return Redirect::route('slide.index')->with('message', 'Carousel bol zmazaný');
        ;
    }

    public function uploadImage($slide)
    {
        $image = Request::file('image');
        $slide->image = $slide->uploadHeaderImage($image);
        $slide->save();

        return true;
    }
}
