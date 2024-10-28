<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMedicineColumnsToPrescriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('prescriptions', function (Blueprint $table) {
            $table->string('med_days')->nullable()->after('usage');
            $table->string('med_unit')->nullable()->after('med_days');
            $table->string('med_time')->nullable()->after('med_unit');
            $table->string('price')->nullable()->after('med_time');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('prescriptions', function (Blueprint $table) {
            $table->dropColumn('med_days');
            $table->dropColumn('med_unit');
            $table->dropColumn('med_time');
            $table->dropColumn('price');
        });
    }
}
