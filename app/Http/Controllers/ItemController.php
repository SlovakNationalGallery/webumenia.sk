<?php

namespace App\Http\Controllers;

use App\Elasticsearch\Repositories\ItemRepository;
use App\Forms\Types\ItemType;
use App\Item;
use App\Collection;
use App\Jobs\HarvestSingleJob;
use Barryvdh\Form\CreatesForms;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use App\SpiceHarvesterRecord;
use Illuminate\Support\Facades\App;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormBuilderInterface;

class ItemController extends Controller
{
    use CreatesForms;

    /** @var FormInterface */
    protected $form;

    /** @var ItemRepository */
    protected $itemRepository;

    public function __construct(ItemRepository $itemRepository)
    {
        $this->itemRepository = $itemRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $items = Item::orderBy('updated_at', 'DESC')->paginate(100);
        // $collections = Collection::orderBy('order', 'ASC')->get();
        $collections = Collection::listsTranslations('name')->pluck('name', 'id')->toArray();
        return view('items.index', array('items' => $items, 'collections' => $collections));
    }

    /**
     * Find and display a listing
     *
     * @return Response
     */
    public function search()
    {

        $search = Input::get('search');
        if (str_contains($search, ';')) {

            $ids = explode(';', str_replace(" ", "", $search));
            $results = Item::whereIn('id', $ids)->paginate(20);
        } else {
            $results = Item::whereTranslationLike('title', '%'.$search.'%')->orWhere('author', 'LIKE', '%'.$search.'%')->orWhere('id', 'LIKE', '%'.$search.'%')->paginate(20);
        }

        $collections = Collection::listsTranslations('name')->pluck('name', 'id')->toArray();
        return view('items.index', array('items' => $results, 'collections' => $collections, 'search' => $search));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $item = Item::find($id);
        return view('items.show')->with('item', $item);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $prefix = 'SVK:TMP.';
        $last_item = Item::where('id', 'LIKE', $prefix.'%')->orderBy('created_at', 'desc')->first();
        $last_number = ($last_item) ? (int)str_after($last_item->id, $prefix) : 0;
        $new_id = $prefix . ($last_number + 1);

        $item = new Item(['id' => $new_id]);
        $form = $this->getItemFormBuilder($item, $new = true)
            ->add('id', null, [
                'disabled' => true,
            ])
            ->getForm();

        return $this->processForm($form);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param string $id
     * @return Response
     */
    public function edit($id)
    {
        $item = Item::find($id) ?: abort(404);
        $form = $this->getItemFormBuilder($item, $new = false)->getForm();

        return $this->processForm($form);
    }

    /**
     * @param Form $form
     * @return Response
     */
    protected function processForm(Form $form)
    {
        $item = $form->getData();
        $form->handleRequest();

        if ($form->isSubmitted() && $form->isValid()) {
            $authors = $form['author']->getData();

            $item->authorities()->sync($authors);

            $authorities = $item->authorities()->orderBy('name', 'asc')->pluck('name')->toArray();

            natcasesort($authorities);

            $item->author = implode(';', $authorities);
            $item->push();

            $tags = $form['tags']->getData();
            if (array_diff($tags, $item->tagSlugs()) != array_diff($item->tagSlugs(), $tags)) {
                $item->retag($tags);
                $this->itemRepository->indexAllLocales($item);
            }

            if ($image = $form['primary_image']->getData()) {
                $item->saveImage($image);
            }

            return Redirect::route('item.index')->with('message', 'Success');
        }

        return view('items.form', [
            'form' => $form->createView(),
            'item' => $item,
        ]);
    }

    /**
     * @param Item $item
     * @param bool $new
     * @return FormBuilderInterface
     */
    protected function getItemFormBuilder(Item $item, $new) {
        return $this->getFormFactory()
            ->createBuilder(ItemType::class, $item, [
                'new' => $new,
                'locales' => config('translatable.locales'),
            ])
            ->add('save', SubmitType::class, [
                'translation_domain' => 'messages',
            ]);
    }

    public function backup()
    {
        $sqlstring = "";
        $table = 'items';
        $newline = "\n";

        $prefix = 'SVK:TMP.';
        $items = Item::where('id', 'LIKE', $prefix.'%')->get();
        foreach ($items as $key => $item) {
            $item_data = $item->toArray();

            $keys = array();
            $values = array();
            foreach ($item_data as $key => $value) {
                $keys[] = "`" . $key . "`";
                if ($value === null) {
                    $value = "NULL";
                } elseif ($value === "" or $value === false) {
                    $value = '""';
                } elseif (!is_numeric($value)) {
                    DB::connection()->getPdo()->quote($value);
                    $value = "\"$value\"";
                }
                $values[] = $value;
            }
            $sqlstring  .= "INSERT INTO `" . $table . "` ( "
                        .  implode(", ", $keys)
                        .    " ){$newline}\tVALUES ( "
                        .  implode(", ", $values)
                        .    " );" . $newline;

        }
        $filename = date('Y-m-d-H-i').'_'.$table.'.sql';
        File::put(app_path() .'/database/backups/' . $filename, $sqlstring);
        return Redirect::back()->withMessage('Záloha ' . $filename . ' bola vytvorená.');

    }

    public function geodata()
    {
        $items = Item::where('place', '!=', '')->get();
        $i = 0;
        foreach ($items as $item) {
            if (!empty($item->place) && (empty($item->lat))) {
                $geoname = Ipalaus\Geonames\Eloquent\Name::where('name', 'like', $item->place)->orderBy('population', 'desc')->first();
                //ak nevratil, skusim podla alternate_names
                if (empty($geoname)) {
                    $geoname = Ipalaus\Geonames\Eloquent\Name::where('alternate_names', 'like', '%'.$item->place.'%')->orderBy('population', 'desc')->first();
                }

                if (!empty($geoname)) {
                    $item->lat = $geoname->latitude;
                    $item->lng = $geoname->longitude;
                    $item->save();
                    $i++;
                }
            }
        }
        return Redirect::back()->withMessage('Pre ' . $i . ' diel bola nastavená zemepisná šírka a výška.');
    }

    /**
     * Destroy selected items
     *
     * @param
     * @return Response
     */
    public function destroySelected()
    {
        $items = Input::get('ids');
        if (!empty($items) > 0) {
            foreach ($items as $item_id) {
                $item = Item::find($item_id);
                $image = $item->getImagePath(true); // fullpath, disable no image
                if ($image) {
                    @unlink($image);
                }
                $item->collections()->detach();

                SpiceHarvesterRecord::where('identifier', '=', $item_id)->delete();

                $item->delete();
            }
        }
        return Redirect::back()->withMessage('Bolo zmazaných ' . count($items) . ' diel');
    }

    /**
     * Refresh selected items with data from OAI-PMH source
     *
     * @param
     * @return Response
     */
    public function refreshSelected()
    {
        $ids = (array)Input::get('ids');
        foreach ($ids as $i => $id) {
            $item = Item::find($id);
            if (isset($item->record)) {
                $this->dispatch(new HarvestSingleJob($item->record));
            } else {
                unset($ids[$i]);
            }
        }
        return Redirect::back()->withMessage('Pre ' . count($ids) . ' diel boli načítané dáta z OAI');
    }

    public function reindex()
    {
        $i = 0;

        Item::with('images')->chunk(200, function ($items) use (&$i) {
            $items->load('authorities');
            foreach ($items as $item) {
                $this->itemRepository->indexAllLocales($item);
                $i++;
                if (App::runningInConsole()) {
                    if ($i % 100 == 0) {
                        echo date('h:i:s'). " " . $i . "\n";
                    }
                }
            }
        });
        $message = 'Bolo reindexovaných ' . $i . ' diel';
        if (App::runningInConsole()) {
            echo $message;
            return true;
        }
        return Redirect::back()->withMessage($message);
    }
}
