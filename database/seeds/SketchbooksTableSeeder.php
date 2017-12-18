<?php

use Illuminate\Database\Seeder;

class SketchbooksTableSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{

        DB::table('sketchbooks')->truncate();
        
        $sketchbook_collection = Collection::find(47);

        foreach ($sketchbook_collection->items as $i=>$item) {
            $sketchbook = new Sketchbook;
            $sketchbook->item_id = $item->id;
            $sketchbook->title = implode(', ', $item->authors)  . ' / ' .  $item->date_latest;
            $sketchbook->order = $i;
            $sketchbook->width = $item->width;
            $sketchbook->height = $item->height;
            // $sketchbook->generated_at = date("Y-m-d H:i:s");
            $sketchbook->save();
        }

	}

}
