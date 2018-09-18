<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Download;
use Yajra\Datatables\Datatables;

class DownloadController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        Carbon::setToStringFormat('d.m.Y H:i');

        if ($request->ajax()) {
            $query = Download::with('item')->select('downloads.*');

            return Datatables::of($query)
                ->addColumn('actions', function ($download) {
                    return
                        link_to_action('DownloadController@show', 'Detail', array($download->id), array('class' => 'btn btn-primary btn-detail btn-xs btn-outline', )) .
                        '&nbsp;<a href="'. $download->item->getUrl() .'" class="btn btn-success btn-xs btn-outline" target="_blank">Na webe</a>';
                     })
                ->make(true);
        }

        return view('downloads.index');
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
