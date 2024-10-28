<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserTermAndConditionStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_term_and_condition_status', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->text('term_and_condition_file');
            $table->integer('status')->default('0');
            $table->string('flag')->default('agreed');
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
        Schema::dropIfExists('user_term_and_condition_status');
    }
}
