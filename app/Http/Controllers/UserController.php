<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;

class UserController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $users = User::orderBy('created_at', 'desc')->paginate(20);
        return view('users.index')->with('users', $users);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('users.form');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        $rules = User::$rules;
        $rules['password'] = 'required'; // pri vytvarani je heslo povinne

        Request::validate($rules);

        $user = new User;
        $user->fill(Request::input());
        $user->password = Hash::make(Request::input('password'));
        $user->save();

        Session::flash('message', 'Užívateľ ' .$user->name. ' bol vytvorený');

        return Redirect::route('user.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $user = User::find($id);
        return view('users.show')->with('user', $user);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $user = User::find($id);

        if (is_null($user)) {
            return Redirect::route('user.index');
        }

        return view('users.form')->with('user', $user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        Request::validate(User::$rules);

        $user = User::find($id);
        $user->fill(Request::input());
        if (Request::filled('password')) {
            $user->password = Hash::make(Request::input('password'));
        }
        $user->save();

        Session::flash('message', 'Užívateľ ' .$user->name. ' bol upravený');
        return Redirect::route('user.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        User::find($id)->delete();
        return Redirect::route('user.index')->with('message', 'Užívateľ bol zmazaný');
        ;
    }
}
