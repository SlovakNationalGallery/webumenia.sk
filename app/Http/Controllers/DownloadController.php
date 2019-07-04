<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Download;
use Yajra\Datatables\Datatables;
use Symfony\Component\HttpFoundation\StreamedResponse;
use DB;

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
            // $query = Download::select([
            //     'downloads.*',
            //     \DB::raw('count(download_item.item_id) as count'),
            // ])->join('download_item','download_item.download_id','=','downloads.id')
            // ->groupBy('download_item.download_id');

            $query = Download::with('items')->select('downloads.*');

            return Datatables::of($query)
                ->addColumn('item_id', function (Download $download) {
                    return $download->items->map(function($item) {
                        return $item->id;
                    })->implode('<br>');
                })
                ->addColumn('actions', function ($download) {
                    return
                        link_to_action('DownloadController@show', 'Detail', array($download->id), array('class' => 'btn btn-primary btn-detail btn-xs btn-outline', ));
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
        $download = Download::find($id)->load('items');

        return view('downloads.show')->with('download', $download);
    }

    /**
     * Download query to CSV file
     *
     * @param int $id
     *
     * @return Response
     */
    public function export(Request $request)
    {
        ini_set('max_execution_time', 300); // 5min
        \DB::connection()->disableQueryLog();

        $query = $query = Download::leftJoin('download_item', 'downloads.id', '=', 'download_item.download_id')->select([
            'downloads.id',
            'downloads.type',
            'downloads.contact_person',
            'downloads.email',
            'downloads.phone',
            DB::raw('GROUP_CONCAT(download_item.item_id SEPARATOR "\n") AS item_ids'),
            'downloads.created_at',
        ])->groupBy('downloads.id');

        $first = true;

        $dt = Carbon::now();
        $date =  $dt->toDateString();
        $filename = 'downloads-'.$date.'.csv';

        $response = new StreamedResponse(function() use ($query, &$first, $filename) {
            $handle = fopen('php://output', 'w');

            $query->chunk(200, function($items) use($handle, &$first) {
                foreach ($items as $item) {

                    if ($first) {
                        // CSV headers
                        fputcsv($handle, array_keys($item->toArray()));
                        $first = false;
                    }

                    fputcsv($handle, $item->toArray());
                }
            });

            fclose($handle);
        }, 200, [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ]);

        return $response;
    }

}
