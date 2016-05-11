<?php

class UserController extends \BaseController
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $users = User::orderBy('created_at', 'desc')->with(['roles'])->paginate(20);
        // $users = Item::orderBy('created_at', 'DESC')->get();
        return View::make('users.index')->with('users', $users);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $roles = Role::orderBy('name', 'asc')->lists('name', 'id');
        return View::make('users.form')->with('roles', $roles);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        $input = Input::all();

        $rules = User::$rules;
        $rules['password'] = 'required|min:6'; //pri vytvarani je heslo povinne

        $v = Validator::make($input, $rules);

        if ($v->passes()) {
            
            $user = new User;
            $user->username = Input::get('username');
            $user->password = Hash::make(Input::get('password'));
            $user->name = Input::get('name');
            $user->email = Input::get('email');
            $user->save();

            $user->roles()->sync([Input::get('roles', '')]);

            Session::flash('message', 'Užívateľ ' .$user->name. ' bol vytvorený');

            return Redirect::route('user.index');
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
        $user = User::find($id);
        return View::make('users.show')->with('user', $user);
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

        $roles = Role::orderBy('name', 'asc')->lists('name', 'id');
        return View::make('users.form')->with('user', $user)->with('roles', $roles);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        $v = Validator::make(Input::all(), User::$rules);

        if ($v->passes()) {
            $input = array_except(Input::all(), array('_method'));

            $user = User::find($id);
            $user->username = Input::get('username');
            if (Input::has('password')) {
                $user->password = Hash::make(Input::get('password'));
            }
            $user->name = Input::get('name');
            $user->email = Input::get('email');
            $user->save();

            $user->roles()->sync([Input::get('roles', '')]);

            Session::flash('message', 'Užívateľ ' .$user->name. ' bol upravený');
            return Redirect::route('user.index');
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
        User::find($id)->delete();
        return Redirect::route('user.index')->with('message', 'Užívateľ bol zmazaný');
        ;
    }
}
