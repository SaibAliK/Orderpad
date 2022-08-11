<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRxesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rxes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('dob');
            $table->foreignId('surgery_id')->constrained()->onDelete('cascade');
            $table->string('email');
            $table->string('order_by');
            $table->string('order_date');
            $table->string('date_needed_by');
            $table->foreignId('pharmacy_id')->references('id')->on('users')->constrained()->onDelete('cascade');
            $table->string('send_via');
            $table->string('comment')->nullable();
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
        Schema::dropIfExists('rxes');
    }
}
