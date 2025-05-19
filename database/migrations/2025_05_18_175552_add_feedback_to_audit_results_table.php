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
        Schema::create('audit_metrics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('audit_result_id')->constrained('audit_results')->onDelete('cascade');
            $table->integer('score')->nullable();
            $table->integer('fcp')->nullable();
            $table->integer('speed_index')->nullable();
            $table->integer('lcp')->nullable();
            $table->float('cls')->nullable();
            $table->json('feedback')->nullable(); // guardando array de mensagens JSON
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_metrics');
    }
};
