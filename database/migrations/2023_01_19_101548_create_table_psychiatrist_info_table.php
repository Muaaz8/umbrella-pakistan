<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTablePsychiatristInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('psychiatrist_info', function (Blueprint $table) {
            $table->id();
            $table->text('doctor_id');
            $table->text('concerns')->nullable;
            $table->text('services')->nullable;
            $table->text('help')->nullable;
            $table->text('skills')->nullable;
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
        Schema::dropIfExists('psychiatrist_info');
    }
}
