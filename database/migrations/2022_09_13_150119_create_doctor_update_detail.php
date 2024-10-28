<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDoctorUpdateDetail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('doctor_profile_update', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->string('name');
            $table->string('last_name');
            $table->string('date_of_birth');
            $table->integer('phone_number');
            $table->string('office_address');
            $table->string('bio');
            $table->string('zip_code');
            $table->string('country_id');
            $table->string('state_id');
            $table->string('city_id');
            $table->string('user_image')->nullable();
            $table->string('edited')->default(0);
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
        Schema::dropIfExists('doctor_update_detail');
    }
}
