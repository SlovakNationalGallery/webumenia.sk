<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;

class FileuploaderController extends Controller
{

    public function upload()
    {
            $input = Request::all();
            $error = '';

            $callback = Request::input('CKEditorFuncNum');

            $rules = array(
                'upload' => 'image|max:15000|required',
            );

            $validation = Validator::make($input, $rules);

            if ($validation->fails()) {
                return response($validation->messages(), 400);
            }

            $file = Request::file('upload');
            $extension = $file->getClientOriginalExtension();
            $filename = md5(date("YmdHis").rand(5, 50)) . "." . $extension;
            $destinationPath = '/images/uploaded/';
            $file->move(public_path() . $destinationPath, $filename);
            $http_path = $destinationPath . $filename;
            // return '<script type="text/javascript">window.parent.CKEDITOR.tools.callFunction('.$callback.',  "'.$http_path.'", "'.$error.'" );</script>';
            return [
                "uploaded" =>  1,
                "fileName" =>  $filename,
                "url" =>  $http_path,
            ];
    }
}
