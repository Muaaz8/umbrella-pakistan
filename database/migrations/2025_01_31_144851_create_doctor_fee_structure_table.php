<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDoctorFeeStructureTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('doctor_fee_approvals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('doctor_id')->nullable(false);
            $table->string('consultation_fee')->nullable();
            $table->string('followup_fee')->nullable();
            $table->enum('is_approved', ['pending', 'accepted', 'cancel'])->default('pending');
            $table->string('approval_time')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('doctor_fee_approvals');
    }
}
