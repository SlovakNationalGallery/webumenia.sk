<?php

namespace App;

use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use SplFileInfo;

class Import extends Model
{
    const STATUS_NEW = 'new';
    const STATUS_QUEUED = 'queued';
    const STATUS_IN_PROGRESS = 'in progress';
    const STATUS_COMPLETED = 'completed';
    const STATUS_ERROR = 'error';
    // const STATUS_KILLED      = 'killed';

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

    public function storage(): Filesystem
    {
        return Storage::disk($this->disk);
    }

    public function files(): Collection
    {
        $files = $this->storage()->files($this->dir_path);
        return collect($files)->map(fn(string $file) => new SplFileInfo($file));
    }

    public function readStream(SplFileInfo $file)
    {
        return $this->storage()->readStream($file);
    }
}
