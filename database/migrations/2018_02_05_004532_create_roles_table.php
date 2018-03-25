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
            $table->{$this->jsonable()}('title')->nullable();
            $table->integer('level')->unsigned()->nullable();
            $table->integer('scope')->nullable();
            $table->auditableAndTimestamps();

            // Indexes
            $table->index(['scope']);
            $table->unique('name');
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

    /**
     * Get jsonable column data type.
     *
     * @return string
     */
    protected function jsonable(): string
    {
        return DB::connection()->getPdo()->getAttribute(PDO::ATTR_DRIVER_NAME) === 'mysql'
               && version_compare(DB::connection()->getPdo()->getAttribute(PDO::ATTR_SERVER_VERSION), '5.7.8', 'ge')
            ? 'json' : 'text';
    }
}
