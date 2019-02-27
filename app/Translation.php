<?php


namespace App;


trait Translation
{
    /*
     * Skips saving
     * Translations are being saved by \Dimsav\Translatable\Translatable::saveTranslations
     */
    public function push() {
        foreach ($this->relations as $models) {
            $models = $models instanceof \Illuminate\Database\Eloquent\Collection
                ? $models->all() : [$models];

            foreach (array_filter($models) as $model) {
                if (! $model->push()) {
                    return false;
                }
            }
        }

        return true;
    }
}