<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;

class AuthController extends Controller
{


    public function getLogin()
    {
        return view('admin.login', array('showLogin'=>true));
    }

    public function postLogin()
    {

        $input = Request::all();

        $rules = array('username' => 'required', 'password' => 'required');

        $v = Validator::make($input, $rules);

        if ($v->passes()) {
            $credentials = array('username' => Request::input('username'), 'password' => Request::input('password'));

            if (Auth::attempt($credentials)) {
                Session::put('debugbar', true);
                return Redirect::intended('/admin');
                // return Redirect::back();
            } else {
                return Redirect::back()->withInput()->withErrors($v);
            }
        }
        
        return redirect('login')->withErrors($v);
    }

    public function logout()
    {
        Session::put('debugbar', false);
        Auth::logout();
        return redirect('/');
    }
}
