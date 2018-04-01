<?php

use Illuminate\Database\Migrations\Migration;

require __DIR__.'/../../database/migrations/create_events_table.php.stub';
require __DIR__.'/../../database/migrations/create_event_tags_table.php.stub';

class CreateProductionTables extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        (new CreateEventsTable())->up();
        (new CreateEventTagsTable())->up();
    }
}
