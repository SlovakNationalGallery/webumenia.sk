<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use SplFileInfo;

class ImportRecord extends Model
{
    const STATUS_NEW = 'new';
    const STATUS_IN_PROGRESS = 'in progress';
    const STATUS_COMPLETED = 'completed';
    const STATUS_ERROR = 'error';

    protected $fillable = ['status', 'started_at', 'filename'];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    protected $attributes = [
        'status' => self::STATUS_NEW,
    ];

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
        return $this->import->files($dir);
    }

    public function iipFiles(): Collection
    {
        $dir = sprintf(
            '%s/%s',
            $this->import->iip_dir_path,
            pathinfo($this->filename, PATHINFO_FILENAME)
        );
        $disk = config('import.iip_disk');
        $files = Storage::disk($disk)->files($dir);
        return collect($files)->map(fn(string $file) => new SplFileInfo($file));
    }

    public function readStream(SplFileInfo $file)
    {
        return $this->import->readStream($file);
    }
}
