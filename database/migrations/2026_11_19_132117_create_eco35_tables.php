<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // 1. Roles en usuarios (Módulo 1)
        Schema::table('users', function (Blueprint $table) {
            $table->enum('rol', ['admin', 'docente', 'estudiante'])->default('estudiante');
        });

        // 2. Catálogo Botánico (Módulo 2)
        Schema::create('especies', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_cientifico');
            $table->decimal('tasa_base', 4, 2); // Ej. 0.85 para 85%
            $table->string('resistencia_hidrica'); // sensible, moderada, baja, resistente
            $table->timestamps();
        });

        // 3. Alertas de Truzas y Semáforo Hídrico (Módulo 3)
        Schema::create('alertas', function (Blueprint $table) {
            $table->id();
            $table->enum('tipo', ['truza', 'agua']);
            $table->enum('semaforo', ['verde', 'amarillo', 'rojo'])->nullable();
            $table->string('descripcion');
            $table->decimal('latitud', 10, 7);
            $table->decimal('longitud', 10, 7);
            $table->foreignId('user_id')->constrained();
            $table->timestamps();
        });

        // 4. Registro de Siembras y Supervivencia (Módulo 4)
        Schema::create('siembras', function (Blueprint $table) {
            $table->id();
            $table->foreignId('especie_id')->constrained('especies');
            $table->integer('cantidad');
            $table->boolean('agua_roja')->default(false);
            $table->integer('supervivientes_estimados');
            $table->foreignId('user_id')->constrained();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('siembras');
        Schema::dropIfExists('alertas');
        Schema::dropIfExists('especies');
    }
};
