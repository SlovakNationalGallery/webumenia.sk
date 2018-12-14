<?php

namespace App\Http\Controllers;

use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;

class ImprovedController extends Controller
{

    /** @var \League\Flysystem\Filesystem */
    protected $fs;


    public function __construct()
    {
        $adapter  = new Local(resource_path('views/components'));
        $this->fs = new Filesystem($adapter);
    }


    public function getIndex()
    {
        $files = $this->fs->listContents('/');

        $result = [];
        foreach ($files as $file) {
            $blade = $file['filename'].'.blade.php';
            if ($file['extension'] === 'json' && $this->fs->has($blade) && $this->fs->has($file['basename'])) {
                $result[] = array_merge($this->processJson($file), [
                    'include_path' => 'components.'.$file['filename'],
                    'source_code'  => $this->fs->read($blade),
                ]);
            }
        }

        return view('patternlib', [
           'components' => $result,
        ]);
    }


    /**
     * Looks for 'data_calls' as well as `include_js` index and mutates the decoded json accordingly.
     *
     * @param Array $file Array like object with file metadata. https://flysystem.thephpleague.com/docs/usage/filesystem-api/#list-contents
     *
     * @return array
     *
     * @throws \League\Flysystem\FileNotFoundException
     * @warning this function uses `eval()` http://php.net/manual/en/function.eval.php
     */
    private function processJson($file)
    {
        $array = json_decode($this->fs->read($file['basename']), true);

        if (isset($array['data_calls']) && is_array($array['data_calls'])) {
            foreach ($array['data_calls'] as $key => $call) {
                // TODO: needs workaround, eval is dangerous
                $array['data'][$key] = eval($call);
            }
        }

        if (isset($array['include_js']) && (bool)$array['include_js'] === true) {
            $array['include_path_js'] = 'components.'.$file['filename'].'_js';
        }

        return $array;
    }
}
