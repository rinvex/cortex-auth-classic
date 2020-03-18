<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(config('cortex.auth.tables.permissions'), function (Blueprint $table) {
            // Columns
            $table->bigInteger('ability_id')->unsigned();
            $table->bigInteger('entity_id')->unsigned();
            $table->string('entity_type', 150);
            $table->boolean('forbidden')->default(false);
            $table->bigInteger('scope')->nullable();

            // Indexes
            $table->index(['scope']);
            $table->index(['ability_id']);
            $table->index(['entity_id', 'entity_type', 'scope'], 'permissions_entity_index');
            $table->foreign('ability_id')->references('id')->on(config('cortex.auth.tables.abilities'))
                 ->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop(config('cortex.auth.tables.permissions'));
    }
}
