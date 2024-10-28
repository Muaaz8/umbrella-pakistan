<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameColumnToDoctorPercentageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('doctor_percentage', function (Blueprint $table) {
            $table->renameColumn('persentage', 'percentage');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('doctor_percentage', function (Blueprint $table) {
            $table->renameColumn('percentage', 'persentage');
            
        });
    }
}
