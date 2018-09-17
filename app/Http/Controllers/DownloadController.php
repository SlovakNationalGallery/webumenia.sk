<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Download;

class DownloadController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        Carbon::setToStringFormat('d.m.Y H:i');
        $downloads = Download::orderBy('created_at', 'desc')->paginate(20);
        return view('downloads.index')->with('downloads', $downloads);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        Carbon::setToStringFormat('d.m.Y H:i');
        $download = Download::find($id);

        return view('downloads.show')->with('download', $download);
    }

}
