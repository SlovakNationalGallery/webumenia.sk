<?php

namespace App;

use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use SplFileInfo;

class Import extends Model
{
    use HasFactory;

    const STATUS_NEW = 'new';
    const STATUS_QUEUED = 'queued';
    const STATUS_IN_PROGRESS = 'in progress';
    const STATUS_COMPLETED = 'completed';
    const STATUS_ERROR = 'error';

    public static $rules = [
        'name' => 'required',
        'class_name' => 'required',
    ];

    protected $dates = ['created_at', 'updated_at', 'started_at', 'completed_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function records()
    {
        return $this->hasMany('App\ImportRecord');
    }

    public function lastRecord()
    {
        return $this->records->last();
    }

    public function setDirPath($value)
    {
        $this->attributes['dir_path'] = $value ?: null;
    }

    public function setQueued()
    {
        $this->attributes['status'] = self::STATUS_QUEUED;
    }

    public function getStatusClassAttribute()
    {
        switch ($this->status) {
            case self::STATUS_COMPLETED:
                return 'success';
                break;

            case self::STATUS_IN_PROGRESS:
                return 'warning';
                break;

            case self::STATUS_ERROR:
                return 'danger';
                break;
        }

        return 'default';
    }

    public function csvFiles(): Collection
    {
        return $this->files()->filter(fn(SplFileInfo $file) => $file->getExtension() === 'csv');
    }

    public function files(string $dir = null): Collection
    {
        $files = $this->storage()->files($dir ?? $this->dir_path);
        return collect($files)->map(fn(string $file) => new SplFileInfo($file));
    }

    public function readStream(SplFileInfo $file)
    {
        return $this->storage()->readStream($file);
    }

    public function fileSize(SplFileInfo $file)
    {
        return $this->storage()->size($file);
    }

    public function lastModified(SplFileInfo $file)
    {
        return $this->storage()->lastModified($file);
    }

    protected function storage(): Filesystem
    {
        return Storage::disk('import');
    }
}
