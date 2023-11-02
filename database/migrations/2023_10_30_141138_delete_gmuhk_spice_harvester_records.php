<?php

use App\Harvest\Harvesters\GmuhkItemHarvester;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        DB::query()
            ->from('spice_harvester_records')
            ->join('spice_harvester_harvests', 'spice_harvester_harvests.id', '=', 'spice_harvester_records.harvest_id')
            ->where('spice_harvester_harvests.type', 'App\\Harvest\\Harvesters\\GmuhkItemHarvester')
            ->delete();
    }

    public function down(): void
    {
        // do nothing
    }
};
