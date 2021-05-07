<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(config('cortex.auth.tables.roles'), function (Blueprint $table) {
            // Columns
            $table->increments('id');
            $table->string('name', 150);
            $table->json('title')->nullable();
            $table->integer('level')->unsigned()->nullable();
            $table->integer('scope')->nullable();
            $table->auditableAndTimestamps();

            // Indexes
            $table->index(['scope']);
            $table->unique(['name', 'scope'], 'roles_name_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop(config('cortex.auth.tables.roles'));
    }
}
