<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_transactions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('transaction_id')->nullable();
            $table->longText('subject')->nullable();
            $table->longText('description')->nullable();
            $table->float('total_amount')->nullable();
            $table->float('balance')->default(0);
            $table->string('currency')->default('USD');
            $table->bigInteger('user_id')->nullable();
            $table->longText('approval_code')->nullable();
            $table->longText('approval_message')->nullable();
            $table->longText('avs_response')->nullable();
            $table->longText('csc_response')->nullable();
            $table->longText('external_transaction_id')->nullable();
            $table->longText('masked_card_number')->nullable();
            $table->integer('status')->default(1);
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
        Schema::dropIfExists('tbl_transactions');
    }
}
