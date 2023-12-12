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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('apellidos', 50)->nullable();
            $table->date('fecha_nacimiento')->nullable();
            $table->string('sexo', 45)->nullable();
            $table->string('direccion_postal', 200)->nullable();
            $table->string('municipio', 50)->nullable();
            $table->string('provincia', 50)->nullable();
            $table->string('imagen_perfil', 100)->nullable();
            $table->string('numero_socio')->unique()->nullable();
            $table->date('fecha_alta')->nullable();
            $table->date('fecha_baja')->nullable();
            $table->string('es_admin', 2)->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};