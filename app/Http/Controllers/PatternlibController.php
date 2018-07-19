<?php

namespace App\Http\Controllers;

class PatternlibController extends Controller
{
    const COMPONENT_BASE_PATH = '../resources/views/components/';

    public function getIndex()
    {            
        // get json file names
        $file_names = array();
        $handle = opendir(self::COMPONENT_BASE_PATH);
        while (false !== ($file_name = readdir($handle))) {
            if (ends_with($file_name, '.json')) {
                $file_names[] = $file_name;
            }
        }
        sort($file_names);

        // add base path
        $file_paths = array();
        foreach ($file_names as $index => $file_name) {
            $file_paths[$index] = self::COMPONENT_BASE_PATH.$file_name;
        }

        // decode json
        $components = array();
        foreach ($file_paths as $file_path) {
            $file_str = file_get_contents($file_path);
            $file_json = json_decode($file_str, true);
            $components[] = $file_json;
        }

        // hydrate attributes
        foreach ($components as $index => &$component) {
            $component = $this->hydrateIncludePath($component, $file_names[$index]);
            $component = $this->hydrateSourceCode($component, $file_paths[$index]);
            $component = $this->hydrateData($component);
            $component = $this->hydrateIncludePathJs($component);
        }
        unset($component); // unset reference to &$component

        return view('patternlib', [
            'components' => $components
        ]);
    }

    private function hydrateIncludePath($component, $file_name)
    {
        $component['include_path'] = 'components.'.substr($file_name, 0, -5);
        return $component;
    }
    
    private function hydrateSourceCode($component, $file_path)
    {
        $component['source_code'] = file_get_contents(substr($file_path, 0, -5).'.blade.php');
        return $component;
    }
    
    private function hydrateData($component)
    {
        if ( isset($component['data_calls']) ) {
            foreach ($component['data_calls'] as $key => $value) {
                $component['data'][$key] = eval($value);
            }
        }
        return $component;
    }

    private function hydrateIncludePathJs($component)
    {
        if ( isset($component['include_js']) ) {
            $component['include_path_js'] = $component['include_path'].'_js';
        }
        return $component;
    }
}
