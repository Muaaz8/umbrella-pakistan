<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableEmailsToBeSent extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('emails_to_be_sent', function (Blueprint $table) {
            $table->id();
            $table->string('reciever_id');
            $table->string('template_name');
            $table->text('markdowndata');
            $table->string('status');
            $table->text('order_cart_item')->nullable();
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
            Schema::dropIfExists('emails_to_be_sent');
    }
}
