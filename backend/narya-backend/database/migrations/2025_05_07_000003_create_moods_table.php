<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('moods', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('color')->nullable();
            $table->timestamps();
        });

        // Insertar los moods predefinidos
        DB::table('moods')->insert([
            ['name' => 'Alegre',       'color' => '#FFE600'],
            ['name' => 'Triste',       'color' => '#1F4E79'],
            ['name' => 'Emocionado',   'color' => '#FF7F11'],
            ['name' => 'Nostálgico',   'color' => '#A67C52'],
            ['name' => 'Deprimido',    'color' => '#607D8B'],
            ['name' => 'Aburrido',     'color' => '#B0BEC5'],
            ['name' => 'Enamorado',    'color' => '#F48FB1'],
            ['name' => 'Ansioso',      'color' => '#4E5D42'],
            ['name' => 'Relajado',     'color' => '#A8D5BA'],
            ['name' => 'Enfadado',     'color' => '#D32F2F'],
            ['name' => 'Motivado',     'color' => '#00B0FF'],
            ['name' => 'Eufórico',     'color' => '#FF4081'],
            ['name' => 'Melancólico',  'color' => '#90A4AE'],
            ['name' => 'Inspirado',    'color' => '#00BFA5'],
            ['name' => 'Inseguro',     'color' => '#D7CCC8'],
            ['name' => 'Conectado',    'color' => '#B39DDB'],
            ['name' => 'Esperanzado',  'color' => '#AED581'],
            ['name' => 'Frustrado',    'color' => '#8B0000'],
            ['name' => 'Solo',         'color' => '#B3E5FC'],
            ['name' => 'Agradecido',   'color' => '#FFD54F'],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('moods');
    }
};
