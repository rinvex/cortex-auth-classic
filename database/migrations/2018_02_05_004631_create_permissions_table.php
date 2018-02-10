<?php

use Silber\Bouncer\Database\Models;
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
        Schema::create(Models::table('permissions'), function (Blueprint $table) {
            // Columns
            $table->integer('ability_id')->unsigned();
            $table->integer('entity_id')->unsigned();
            $table->string('entity_type', 150);
            $table->boolean('forbidden')->default(false);
            $table->integer('scope')->nullable();

            // Indexes
            $table->index(['scope']);
            $table->index(['ability_id']);
            $table->index(['entity_id', 'entity_type', 'scope'], 'permissions_entity_index');
            $table->foreign('ability_id')->references('id')->on(Models::table('abilities'))
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
        Schema::drop(Models::table('permissions'));
    }
}
