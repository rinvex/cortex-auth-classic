<?php

declare(strict_types=1);
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssignedRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(config('cortex.fort.tables.assigned_roles'), function (Blueprint $table) {
            // Columns
            $table->integer('role_id')->unsigned();
            $table->integer('entity_id')->unsigned();
            $table->string('entity_type', 150);
            $table->integer('scope')->nullable();

            // Indexes
            $table->index(['scope']);
            $table->index(['role_id']);
            $table->index(['entity_id', 'entity_type', 'scope'], 'assigned_roles_entity_index');
            $table->foreign('role_id')->references('id')->on(config('cortex.fort.tables.roles'))
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
        Schema::drop(config('cortex.fort.tables.assigned_roles'));
    }
}
