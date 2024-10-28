<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestLabsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quest_labs', function (Blueprint $table) {
            $table->id();
            $table->string('quest_patient_id');
            $table->integer('umd_patient_id');
            // b.2.2
            $table->text('abn');
            $table->string('billing_type');
            $table->string('diagnosis_code');
            // b.2.3
            $table->integer('vendor_account_id');
            // b.2.4 
            $table->string('orderedtestcode');
            $table->string('names');
            $table->text('aoe');
            $table->date('collect_date');
            $table->time('collect_time');
            // b.2.5
            $table->text('lab_reference_num');
            // b.2.6
            $table->string('npi');
            // b.2.7
            $table->string('ssn');
            $table->string('room');
            $table->string('result_notification');
            $table->string('insurance_num');
            $table->string('group_num');
            $table->string('relation');
            $table->string('upin');
            $table->string('ref_physician_id');
            $table->string('temp');
            $table->string('icd_diagnosis_code');
            // b.2.9
            $table->boolean('psc_hold')->default(0);
            // b.2.10
            $table->string('barcode');
            // b.2.11
            $table->text('specimen_labels');
            // b.2.12
            $table->string('comment');
            // b.3.1
            $table->string('client_bill');
            // b.3.2
            $table->string('patient_bill');
            // b.3.3
            $table->string('third_party_bill');

            $table->timestamps();
        });
        Schema::table('quest_labs', function (Blueprint $table) {
            // $table->foreign('vendor_account_id')->references('id')->on('vendor_accounts');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('quest_labs');
    }
}
