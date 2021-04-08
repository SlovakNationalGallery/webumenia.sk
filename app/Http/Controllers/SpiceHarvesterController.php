<?php

namespace App\Http\Controllers;

use App\Jobs\HarvestFailedJob;
use App\Jobs\HarvestRecordJob;
use App\Jobs\HarvestJob;
use App\SpiceHarvesterHarvest;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use App\Collection;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use App\Item;
use App\SpiceHarvesterRecord;
use Illuminate\Support\Facades\Request;

class SpiceHarvesterController extends Controller
{

    const OAI_DATE_FORMAT = 'Y-m-d';

    const METADATA_SCHEMA = 'http://www.openarchives.org/OAI/2.0/oai_dc.xsd';
    const METADATA_PREFIX = 'oai_dc';

    const OAI_DC_NAMESPACE = 'http://www.openarchives.org/OAI/2.0/oai_dc/';
    const DUBLIN_CORE_NAMESPACE_ELEMTS = 'http://purl.org/dc/elements/1.1/';
    const DUBLIN_CORE_NAMESPACE_TERMS = 'http://purl.org/dc/terms/';

    protected $exclude_prefix = array('x');
    protected $log;


    public function __construct()
    {
        $logFile = 'oai_harvest.log';
        $this->log = new \Monolog\Logger('oai_harvest');
        $this->log->pushHandler(new \Monolog\Handler\StreamHandler(storage_path().'/logs/'.$logFile, \Monolog\Logger::WARNING));
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $harvests = SpiceHarvesterHarvest::orderBy('created_at', 'DESC')->paginate(10);
        return view('harvests.index')->with('harvests', $harvests);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('harvests.form');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        $input = Request::all();

        $rules = SpiceHarvesterHarvest::$rules;
        $v = Validator::make($input, $rules);

        if ($v->passes()) {

            $harvest = new SpiceHarvesterHarvest;
            $harvest->base_url = Request::input('base_url');
            $harvest->type = Request::input('type');
            $harvest->metadata_prefix = Request::input('metadata_prefix');
            $harvest->set_spec = Request::input('set_spec');
            $harvest->set_name = Request::input('set_name');
            $harvest->set_description = Request::input('set_description');
            $collection = Collection::find(Request::input('collection_id'));
            if ($collection) {
                $harvest->collection()->associate($collection);
            }
            if (Request::has('username') && Request::has('password')) {
                $harvest->username = Request::input('username');
                $harvest->password = Request::input('password');
            }
            $harvest->save();

            return Redirect::route('harvests.index');
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
        $harvest = SpiceHarvesterHarvest::find($id);
        $harvest->load('collection');
        return view('harvests.show')->with('harvest', $harvest);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $harvest = SpiceHarvesterHarvest::find($id);

        if (is_null($harvest)) {
            return Redirect::route('harvest.index');
        }

        return view('harvests.form')->with('harvest', $harvest);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        $v = Validator::make(Request::all(), SpiceHarvesterHarvest::$rules);

        if ($v->passes()) {
            $input = Arr::except(Request::all(), array('_method'));

            $harvest = SpiceHarvesterHarvest::find($id);
            $harvest->base_url = Request::input('base_url');
            $harvest->type = Request::input('type');
            if (Request::has('username') && Request::has('password')) {
                $harvest->username = Request::input('username');
                $harvest->password = Request::input('password');
            }
            $harvest->metadata_prefix = Request::input('metadata_prefix');
            $harvest->set_spec = Request::input('set_spec');
            $harvest->set_name = Request::input('set_name');
            $harvest->set_description = Request::input('set_description');
            // $collection = \Collection::find(Request::input('collection_id'));
            // if ($collection->count()) $harvest->collection()->associate($collection);
            $harvest->collection_id = Request::input('collection_id');
            $harvest->cron_status = Request::input('cron_status');
            $harvest->save();

            Session::flash('message', 'Harvest <code>'.$harvest->set_spec.'</code> bol upravený');
            return Redirect::route('harvests.index');
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
        $harvest = SpiceHarvesterHarvest::find($id);
        $set_spec = $harvest->set_spec;
        foreach ($harvest->records as $i => $record) {
            $record->delete();
        }
        $harvest->delete();
        return Redirect::route('harvests.index')->with('message', 'Harvest <code>'.$set_spec.'</code>  bol zmazaný');
        ;
    }

    public function orphaned($id)
    {
        $processed_items = 0;
        $removed_items = 0;
        $timeStart = microtime(true);
        $from = null;

        $harvest = SpiceHarvesterHarvest::find($id);
        $client = new \Phpoaipmh\Client($harvest->base_url);
        $myEndpoint = new \Phpoaipmh\Endpoint($client);

        $items_to_remove = array();

        foreach ($harvest->records as $i => $record) {
            $processed_items++;
            $remove_id = true;
            $rec = $myEndpoint->getRecord($record->item_id, $harvest->metadata_prefix);
            if (!empty($rec)) {
                $setSpecs = (array) $rec->GetRecord->record->header->setSpec;
                // if ($setSpec==$harvest->set_spec) {
                if (in_array($harvest->set_spec, $setSpecs)) {
                    $remove_id = false;
                }
            }
            if ($remove_id) {
                $items_to_remove[] = $record->item_id;
            }
        }

        $collections = Collection::listsTranslations('name')->pluck('name', 'id')->toArray();
        if (count($items_to_remove)) {
            $items = Item::whereIn('id', $items_to_remove)->paginate('50');
        } else {
            $items = Item::where('id', '=', 0);
        }
        Session::flash('message', 'Našlo sa ' .count($items_to_remove). ' záznamov, ktoré sa už nenachádzajú v OAI sete ' . $harvest->set_name . ':');
        return view('items.index', array('items' => $items, 'collections' => $collections));
    }

    public function launch($id)
    {
        $harvest = SpiceHarvesterHarvest::findOrFail($id);
        $from = Request::has('start_date') ? new Carbon(Request::input('start_date')) : null;
        $to = Request::has('end_date') ? new Carbon(Request::input('end_date')) : null;
        $all = Request::input('reindex', false);

        $this->dispatch(new HarvestJob($harvest, $from, $to, $all));
        return Redirect::back();
    }

    public function refreshRecord($record_id)
    {
        $record = SpiceHarvesterRecord::findOrFail($record_id);
        $this->dispatch(new HarvestRecordJob($record));
        $message = 'Pre záznam boli úspešne načítané dáta z OAI';
        Session::flash('message', $message);
        return Redirect::back();
    }

    public function harvestFailed($id)
    {
        $harvest = SpiceHarvesterHarvest::findOrFail($id);
        $this->dispatch(new HarvestFailedJob($harvest));
        Session::flash('message', 'Harvest failed job has been queued');
        return Redirect::back();
    }
}
