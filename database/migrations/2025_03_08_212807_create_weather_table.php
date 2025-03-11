<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('weather', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->unique();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->string('location'); // Nombre de la ubicación
            $table->string('region'); // Región
            $table->string('country'); // País
            $table->timestamp('localtime'); // Hora local

            $table->float('temp_c'); // Temperatura en Celsius
            $table->integer('is_day'); // Si es de día (1) o de noche (0)
            $table->string('condition_text'); // Descripción del clima
            $table->string('condition_icon'); // URL del ícono del clima
            $table->string('wind_dir'); // Dirección del viento
            $table->float('wind_kph'); // Velocidad del viento en kph
            $table->float('feelslike_c'); // Temperatura "real" en Celsius
            $table->float('gust_kph'); // Ráfagas de viento en kph
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('weather');
    }
};
