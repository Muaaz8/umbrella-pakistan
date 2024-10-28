<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContractsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->nullable();
            $table->integer('provider_id')->nullable();
            $table->string('provider_name')->nullable();
            $table->text('provider_address')->nullable();
            $table->string('provider_email_address')->nullable();
            $table->string('provider_speciality')->nullable();
            $table->string('date')->nullable();
            $table->integer('monthly_retainer_fee')->nullable();
            $table->text('signature')->nullable();
            $table->string('status')->nullable();
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
        Schema::dropIfExists('contracts');
    }
}
