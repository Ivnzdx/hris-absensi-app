<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('shifts', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Contoh: Shift Pagi, Shift Siang
            $table->time('start_time'); // Jam Masuk, misal: 08:00:00
            $table->time('end_time');   // Jam Pulang, misal: 17:00:00
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('shifts');
    }
};