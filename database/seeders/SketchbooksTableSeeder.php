<?php

namespace Database\Seeders;

use App\Collection;
use App\Sketchbook;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SketchbooksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sketchbook_collection = Collection::find(47);

        foreach ($sketchbook_collection->items as $i => $item) {
            $sketchbook = new Sketchbook();
            $sketchbook->item_id = $item->id;
            $sketchbook->title = implode(', ', $item->authors) . ' / ' . $item->date_latest;
            $sketchbook->order = $i;
            $sketchbook->width = $item->width;
            $sketchbook->height = $item->height;
            $sketchbook->save();
        }
    }
}
