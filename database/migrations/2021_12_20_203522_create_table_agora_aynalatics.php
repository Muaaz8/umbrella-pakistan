<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableAgoraAynalatics extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('table_agora_aynalatics', function (Blueprint $table) {
            $table->id();
            $table->text('resID')->nullable();
            $table->text('sID')->nullable();
            $table->string('channel')->nullable();
            $table->string('userID')->nullable();
            $table->text('video_link')->nullable();
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
        Schema::dropIfExists('table_agora_aynalatics');
    }
}
