<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DeleteFormerlyAttributedToAuthors extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $items = DB::table('authority_item')
            ->join('authorities', function ($join) {
                $join->on('authorities.id', '=', 'authority_item.authority_id');
            })
            ->join('items', function ($join) {
                $join->on('items.id', '=', 'authority_item.item_id');
            })
            ->select('item_id', 'author', 'name')
            ->where('role', 'pôvodné určenie/formerly attributed to')
            ->get();

        foreach ($items as $item) {
            $authors = Str::of($item->author)
                ->explode(';')
                ->map(function ($author) {
                    return trim($author);
                })
                ->reject(function ($author) use ($item) {
                    return $author === $item->name;
                })
                ->join('; ');

            DB::table('items')
                ->where('id', $item->item_id)
                ->update(['author' => $authors]);
        }

        DB::table('authority_item')
            ->where('role', 'pôvodné určenie/formerly attributed to')
            ->delete();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
