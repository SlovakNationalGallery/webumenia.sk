<?php

namespace App;

use App\Harvest\Harvesters\MudbItemHarvester;
use App\Harvest\Progress;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use App\Harvest\Harvesters\AuthorityHarvester;
use App\Harvest\Harvesters\GmuhkItemHarvester;
use App\Harvest\Harvesters\ItemHarvester;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SpiceHarvesterHarvest extends Model
{
    use HasFactory;

    const STATUS_QUEUED = 'queued';
    const STATUS_IN_PROGRESS = 'in progress';
    const STATUS_COMPLETED = 'completed';
    const STATUS_ERROR = 'error';
    const STATUS_DELETED = 'deleted';
    const STATUS_KILLED = 'killed';

    public static $types = [
        ItemHarvester::class => 'Dielo',
        GmuhkItemHarvester::class => 'Dielo (GMUHK)',
        MudbItemHarvester::class => 'Dielo (MUDB)',
        AuthorityHarvester::class => 'Autorita',
    ];

    protected $appends = ['from'];
    public static $datum;
    public static $cron_statuses = ['manual' => 'Manual', 'daily' => 'Daily', 'weekly' => 'Weekly'];
    // public static $cron_status;

    public static $rules = [
        'base_url' => 'required',
        'metadata_prefix' => 'required',
    ];

    public function records()
    {
        return $this->hasMany('App\SpiceHarvesterRecord', 'harvest_id');
    }

    public function collection()
    {
        return $this->belongsTo('App\Collection');
    }

    public function getFromAttribute()
    {
        return $this->start_from;
    }

    public function getStatusClassAttribute()
    {
        switch ($this->status) {
            case self::STATUS_COMPLETED:
                return 'success';
                break;

            case self::STATUS_IN_PROGRESS:
            case self::STATUS_QUEUED:
                return 'warning';
                break;

            case self::STATUS_ERROR:
                return 'danger';
                break;
        }

        return 'default';
    }

    public function process(callable $onProcess)
    {
        $progress = new Progress();

        try {
            $this->status = SpiceHarvesterHarvest::STATUS_IN_PROGRESS;
            $this->status_messages = trans('harvest.status_messages.started');
            $this->save();

            $onProcess($progress);

            $this->status = SpiceHarvesterHarvest::STATUS_COMPLETED;
            $this->completed = date('Y-m-d H:i:s');
            $this->status_messages = trans('harvest.status_messages.completed', [
                'processed' => $progress->getProcessed(),
                'created' => $progress->getInserted(),
                'updated' => $progress->getUpdated(),
                'deleted' => $progress->getDeleted(),
                'skipped' => $progress->getSkipped(),
                'failed' => $progress->getFailed(),
                'time' => Carbon::now()->diffInSeconds($progress->getCreatedAt()),
            ]);
        } catch (\Exception $e) {
            $this->status = SpiceHarvesterHarvest::STATUS_ERROR;
            $this->status_messages = trans('harvest.status_messages.error', [
                'error' => $e->getMessage(),
            ]);
        } finally {
            $this->save();
        }
    }

    public function updateStatusMessages(Progress $progress)
    {
        $this->status_messages = trans('harvest.status_messages.progress', [
            'current' => $progress->getProcessed(),
            'total' => $progress->getTotal(),
        ]);
        $this->save();
    }

    public function enqueue()
    {
        $this->status = SpiceHarvesterHarvest::STATUS_QUEUED;
        $this->status_messages = trans('harvest.status_messages.waiting');
        $this->save();
    }
}
