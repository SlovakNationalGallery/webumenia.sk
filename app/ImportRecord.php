<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use SplFileInfo;

class ImportRecord extends Model
{
    protected $fillable = ['status', 'started_at', 'filename'];

    protected $dates = ['created_at', 'updated_at', 'started_at', 'completed_at'];

    public function import()
    {
        return $this->belongsTo('App\Import');
    }

    public function files(): Collection
    {
        $dir = sprintf(
            '%s/%s',
            $this->import->dir_path,
            pathinfo($this->filename, PATHINFO_FILENAME)
        );
        $files = $this->import->storage()->files($dir);
        return collect($files)->map(fn(string $file) => new SplFileInfo($file));
    }

    public function readStream(SplFileInfo $file)
    {
        return $this->import->storage()->readStream($file);
    }
}
