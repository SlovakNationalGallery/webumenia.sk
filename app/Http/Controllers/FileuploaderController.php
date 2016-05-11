<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;


class FileuploaderController extends \BaseController
{

    public function upload()
    {
            $input = Input::all();
            $error = '';

            $callback = Input::get('CKEditorFuncNum');
     
            $rules = array(
                'upload' => 'image|max:15000|required',
            );
     
            $validation = Validator::make($input, $rules);
     
            if ($validation->fails()) {
                return response($validation->messages(), 400);
            }
     
            $file = Input::file('upload');
            $extension = $file->getClientOriginalExtension();
            $filename = md5(date("YmdHis").rand(5, 50)) . "." . $extension;
            $destinationPath = '/images/uploaded/';
            $file->move(public_path() . $destinationPath, $filename);
            $http_path = $destinationPath . $filename;
            return '<script type="text/javascript">window.parent.CKEDITOR.tools.callFunction('.$callback.',  "'.$http_path.'", "'.$error.'" );</script>';
            // return URL::to('/images/uploaded/' . $filename);
    }
}
