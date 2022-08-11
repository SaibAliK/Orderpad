<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePatientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('patient')->nullable();
            $table->foreignId('surgery_id')->constrained()->onDelete('cascade');
            $table->bigInteger('weeks');
            $table->string('ordering_method');
            $table->date('order_date');
            $table->date('medication_date');
            $table->bigInteger('order_no');
            $table->date('previous_order_date')->nullable();
            $table->date('next_order_date')->nullable();
            $table->foreignId('pharmacy_id')->references('id')->on('users')->constrained()->onDelete('cascade');
            $table->date('previous_medication_date')->nullable();
            $table->date('next_medication_date')->nullable();
            $table->string('notes')->nullable();
            $table->string('order_gap')->nullable();
            $table->string('order_gap_achieved')->nullable();
            $table->tinyInteger('order_gap_status')->default('0');
            $table->integer('order_gap_now')->nullable();
            $table->tinyInteger('status')->default('0');
            $table->string('date')->nullable();
            $table->string('medication')->nullable();
            $table->string('pendingOrder')->default('false')->nullable();
            $table->string('other_ordering_method')->nullable();
            $table->timestamp('pending_status_date')->nullable();
            $table->foreignId('rx_id')->references('id')->on('rxes')->constrained()->onDelete('cascade');
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
        Schema::dropIfExists('patients');
    }
}
