<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;

use App\Import;
use App\Jobs\ImportCsv;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Translation\Translator;

class ImportController extends Controller
{
    protected $translator;

    public function __construct(Translator $translator)
    {
        $this->translator = $translator;
        $this->authorizeResource(Import::class, 'import');
    }

    public function index()
    {
        $imports = Import::orderBy('created_at', 'desc');

        if (Gate::denies('viewAll', Import::class)) {
            $imports = $imports->where('user_id', Auth::user()->id);
        }

        return view('imports.index')->with('imports', $imports->paginate(20));
    }

    public function create()
    {
        return view('imports.form')->with([
            'disabled_metadata_fields' => false,
        ]);
    }

    public function store()
    {
        $input = Request::all();

        $rules = Import::$rules;
        $v = Validator::make($input, $rules);

        if ($v->passes()) {
            $import = new Import();
            $import->name = Request::input('name');
            $import->class_name = Request::input('class_name');
            $import->dir_path = Request::input('dir_path');
            $import->iip_dir_path = Request::input('iip_dir_path');
            $import->user_id = Request::input('user_id');
            $import->save();

            return redirect()->route('imports.index');
        }

        return back()
            ->withInput()
            ->withErrors($v);
    }

    public function show(Import $import)
    {
        return view('imports.show')->with([
            'import' => $import,
            'records' => $import
                ->records()
                ->orderBy('id', 'desc')
                ->take(50)
                ->get(),
        ]);
    }

    public function edit(Import $import)
    {
        $options = $import->class_name::getOptions();

        return view('imports.form')->with([
            'import' => $import,
            'options' => $options,
            'disabled_metadata_fields' => Gate::denies('updateMetadata', $import),
        ]);
    }

    public function update(Import $import)
    {
        if (Gate::allows('updateMetadata', $import)) {
            $v = Validator::make(Request::all(), Import::$rules);
            if ($v->passes()) {
                $import->name = Request::input('name');
                $import->class_name = Request::input('class_name');
                $import->dir_path = Request::input('dir_path');
                $import->iip_dir_path = Request::input('iip_dir_path');
                $import->user_id = Request::input('user_id');
                $import->save();
            } else {
                return back()->withErrors($v);
            }
        }

        if (Gate::allows('updateFile', $import) && Request::hasFile('file')) {
            $file = Request::file('file');
            $path_to_save = sprintf(
                'import/%s/%s',
                $import->dir_path,
                $file->getClientOriginalName()
            );
            Storage::put($path_to_save, File::get($file));
        }

        Session::flash('message', 'Import <code>' . $import->name . '</code> bol upravený');
        return redirect()->route('imports.index');
    }

    public function destroy(Import $import)
    {
        $name = $import->name;
        $import->delete();
        return redirect()
            ->route('imports.index')
            ->with('message', 'Import <code>' . $name . '</code>  bol zmazaný');
    }

    public function launch(Import $import)
    {
        Gate::authorize('launch', $import);

        // run in queue
        $import->status = Import::STATUS_QUEUED;
        $import->started_at = date('Y-m-d H:i:s');
        $import->save();

        $this->dispatch(new ImportCsv($import));

        return redirect()
            ->route('imports.index')
            ->with('message', 'Import <code>' . $import->name . '</code> bol spustený');
    }
}
