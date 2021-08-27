<?php

use App\Slide;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MigrateSlidesImagesToMedia extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $disk = config('media-library.disk_name');
        $storage = Storage::disk($disk);
        $slides = Slide::whereNotNull('image')->where('image', '<>', '');

        foreach ($slides->lazy() as $slide) {
            $imagePath = 'public/images/intro/' . $slide->image;
            $fileName = File::basename($imagePath);
            
            if (!File::exists($imagePath)) {
                echo $imagePath . ' does not exist. Skipping...' . PHP_EOL;
                continue;
            }

            DB::transaction(function () use ($disk, $storage, $slide, $imagePath, $fileName) {
                $id = DB::table('media')
                    ->insertGetId([
                        'model_type' => 'App\Slide',
                        'model_id' => $slide->id,
                        'uuid' => Str::uuid(),
                        'collection_name' => 'image',
                        'name' => pathinfo($imagePath, PATHINFO_FILENAME),
                        'file_name' => $fileName,
                        'mime_type' => File::mimeType($imagePath),
                        'disk' => $disk,
                        'conversions_disk' => $disk,
                        'size' => File::size($imagePath),
                        'manipulations' => '[]',
                        'custom_properties' => '[]',
                        'generated_conversions' => '[]',
                        'responsive_images' => '[]',
                        'order_column' => $slide->id,
                        'created_at' => DB::raw('NOW()'),
                        'updated_at' => DB::raw('NOW()'),
                    ]);

                $storage->makeDirectory($id);
                File::copy($imagePath, $storage->path("$id/$fileName"));
            });
        }

        Schema::table('slides', function (Blueprint $table) {
            $table->dropColumn('image');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('slides', function (Blueprint $table) {
            $table->string('image')->after('url');
        });

        foreach(DB::table('media')->where('model_type', 'App\Slide')->lazyById() as $media) {
            DB::table('slides')
                ->where('id', $media->model_id)
                ->update(['image' => "{$media->model_id}/{$media->file_name}"]);

            Storage::disk($media->disk)->deleteDirectory($media->id);

            DB::table('media')->where('id', $media->id)->delete();
        }
    }
}
