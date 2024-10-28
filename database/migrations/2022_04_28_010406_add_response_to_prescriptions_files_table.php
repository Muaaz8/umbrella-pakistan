<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddResponseToPrescriptionsFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('prescriptions_files', function (Blueprint $table) {
            $table->text('response')->nullable()->after('filename');
            $table->text('status')->nullable()->after('response');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('prescriptions_files', function (Blueprint $table) {
            $table->dropColumn('response');
            $table->dropColumn('status');
        });
    }
}
