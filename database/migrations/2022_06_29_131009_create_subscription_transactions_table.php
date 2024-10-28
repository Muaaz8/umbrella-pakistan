<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscriptionTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscription_transactions', function (Blueprint $table) {
            $table->id();
            $table->integer('uid');
            $table->string('customer_id');
            $table->text('masked_card_number');
            $table->string('recurrence_id')->nullable();
            $table->text('start_date')->nullable();
            $table->string('total_amount')->nullable();
            $table->integer('refund_flag')->default(0);
            $table->integer('refund_amount')->default(0);
            $table->string('currency_type')->default('USD');
            $table->string('response_code')->nullable();
            $table->string('status_message')->nullable();
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
        Schema::dropIfExists('subscription_transactions');
    }
}
