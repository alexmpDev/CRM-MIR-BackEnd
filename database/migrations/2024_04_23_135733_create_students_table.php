<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('surname1', 255);
            $table->string('surname2', 255);
            $table->string('dni', 255);//no mostrar en ningun show ni lista usar solo en bd
            $table->date('birthDate');
            $table->string('curs', 255);
            $table->string('photo', 255)->nullable();
            $table->boolean('leave')->default(false);
            $table->string('qr',255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
