<?php

namespace App\Console\Commands;

use App\Item;
use App\ItemImage;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ImportIipImages extends Command
{
    protected $signature = 'items:import-images {dir}';

    protected $description = 'Import item images';

    public function handle()
    {
        $dir = $this->argument('dir');
        $files = Storage::disk('import')
            ->listContents($dir)
            ->sortByPath();

        $imported = $alreadyExisting = 0;

        foreach ($files as $file) {
            if (!preg_match('/^(.*?)--(.*?)--.*\.jp2$/', basename($file->path()), $matches)) {
                continue;
            }

            $id = sprintf('SVK:%s.%s', $matches[1], $matches[2]);
            $item = Item::find($id);

            if (!$item) {
                $this->error(sprintf('Item %s does not exist', $id));
                continue;
            }

            $containsImage = $item->images->contains(function (ItemImage $image) use ($file) {
                return $image->iipimg_url === $file->path();
            });

            if ($containsImage) {
                $alreadyExisting++;
                continue;
            }

            ItemImage::create([
                'iipimg_url' => $file->path(),
                'item_id' => $id,
            ]);

            $imported++;
        }

        $this->output->writeln(
            sprintf('Imported %d image(s), %d already existing', $imported, $alreadyExisting)
        );
    }
}
