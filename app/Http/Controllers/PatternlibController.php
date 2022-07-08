<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use League\Flysystem\Filesystem;
use League\Flysystem\Local\LocalFilesystemAdapter;
use League\Flysystem\StorageAttributes;

class PatternlibController extends Controller
{
    /** @var \League\Flysystem\Filesystem */
    protected $fs;

    public function __construct()
    {
        $adapter = new LocalFilesystemAdapter(resource_path('views/components'));
        $this->fs = new Filesystem($adapter);
    }

    public function getIndex()
    {
        $components = $this->fs
            ->listContents('/', true)
            ->filter(
                fn(StorageAttributes $a) => $a->isFile() &&
                    Str::endsWith($a->path(), '.json') &&
                    $this->fs->has(Str::replaceLast('.json', '.blade.php', $a->path()))
            )
            ->map(
                fn(StorageAttributes $a) => array_merge($this->processJson($a), [
                    'include_path' => 'components.' . basename($a->path(), '.json'),
                    'source_code' => $this->fs->read(
                        Str::replaceLast('.json', '.blade.php', $a->path())
                    ),
                ])
            );

        return view('patternlib', [
            'components' => $components,
        ]);
    }

    /**
     * Looks for 'data_calls' as well as `include_js` index and mutates the decoded json accordingly.
     *
     * @warning this function uses `eval()` http://php.net/manual/en/function.eval.php
     */
    private function processJson(StorageAttributes $file)
    {
        $array = json_decode($this->fs->read($file->path()), true);

        if (isset($array['data_calls']) && is_array($array['data_calls'])) {
            foreach ($array['data_calls'] as $key => $call) {
                // TODO: needs workaround, eval is dangerous
                $array['data'][$key] = eval($call);
            }
        }

        if (isset($array['include_js']) && (bool) $array['include_js'] === true) {
            $js_filename = Str::replaceLast('.json', '_js.blade.php', $file->path());
            if ($this->fs->has($js_filename)) {
                // first check for _js.blade.php
                $array['include_path_js'] =
                    'components.' . basename($file->path(), '.json') . '_js';
            } else {
                // otherwise use the static js file within js/components/
                $js_filename = $file->path() . '.js';
                $array['js_asset'] = $js_filename;
            }
        }

        return $array;
    }
}
