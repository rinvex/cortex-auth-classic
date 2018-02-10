<?php

use Silber\Bouncer\Database\Models;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAbilitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(Models::table('abilities'), function (Blueprint $table) {
            // Columns
            $table->increments('id');
            $table->string('name', 150);
            $table->{$this->jsonable()}('title')->nullable();
            $table->integer('entity_id')->unsigned()->nullable();
            $table->string('entity_type', 150)->nullable();
            $table->boolean('only_owned')->default(false);
            $table->integer('scope')->nullable();
            $table->auditable();
            $table->timestamps();

            // Indexes
            $table->index(['scope']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop(Models::table('abilities'));
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
