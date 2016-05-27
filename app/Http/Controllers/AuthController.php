<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;

class AuthController extends Controller
{


    public function getLogin()
    {
        return view('admin.login', array('showLogin'=>true));
    }

    public function postLogin()
    {

        $input = Input::all();

        $rules = array('username' => 'required', 'password' => 'required');

        $v = Validator::make($input, $rules);

        if ($v->passes()) {
            $credentials = array('username' => Input::get('username'), 'password' => Input::get('password'));

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
