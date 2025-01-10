<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dependents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('caregiver_id')->constrained('users');
            $table->string('name');
            $table->integer('age');
            $table->string('relationship');
            $table->string('avatar_url')->nullable();
            $table->string('border_color')->default('#000000');
            $table->boolean('requires_password')->default(false);
            $table->string('username')->nullable();
            $table->string('password')->nullable();
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
        Schema::dropIfExists('dependents');
    }
};
