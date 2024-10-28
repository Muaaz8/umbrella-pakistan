<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quest_invoices', function (Blueprint $table) {
            $table->id();
            $table->text('Order_id')->nullable();
            $table->text('Invoice_number')->nullable();
            $table->text('Services')->nullable();
            $table->text('CPT')->nullable();
            $table->text('Service_code')->nullable();
            $table->text('Amount')->nullable();
            $table->text('Draw_fee')->nullable();
            $table->text('Profit')->nullable();
            $table->text('Status')->nullable();
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
        Schema::dropIfExists('quest_invoices');
    }
}
