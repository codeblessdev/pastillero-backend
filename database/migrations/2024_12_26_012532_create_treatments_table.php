<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTreatmentsTable extends Migration
{
    public function up()
    {
        Schema::create('treatments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // Relación con usuario o dependiente
            $table->string('name');
            $table->string('icon_url')->nullable();
            $table->integer('units_available');
            $table->integer('units_per_dose');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->string('schedule_type'); // 'daily', 'specific', etc.
            $table->json('schedule'); // Detalles de horarios
            $table->boolean('is_chronic')->default(false);
            $table->date('expiration_date')->nullable();
            $table->boolean('notify_low_stock')->default(false);
            $table->boolean('notify_expiration')->default(false);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('treatments');
    }
};
