<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableIsabelAgeGroup extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('isabel_age_group', function (Blueprint $table) {
            $table->id();
            $table->text('agegroup_id');
            $table->text('name');
            $table->text('yr_from');
            $table->text('yr_to');
            $table->text('branch');
            $table->text('can_conceive');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('table_isabel_age_group');
    }
}
