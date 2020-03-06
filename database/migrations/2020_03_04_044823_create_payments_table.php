<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('uuid');
            $table->timestamp('payment_date', 0)->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->enum('status', ['paid', 'pending', 'dued','cancelled']);
            $table->unsignedBigInteger('user_id');
            $table->double('clp_usd', 10, 2)->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('clients');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
    }
}
