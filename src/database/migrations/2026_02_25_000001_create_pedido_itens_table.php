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
        Schema::create('pedido_itens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pedido_id')->constrained()->cascadeOnDelete();
            $table->string('item')->nullable();           // Item
            $table->string('material')->nullable();       // Material
            $table->string('denominacao')->nullable();    // Denominação
            $table->decimal('qtd', 12, 4)->nullable();   // Qtd.
            $table->string('un')->nullable();             // Un.
            $table->decimal('preco', 12, 4)->nullable();  // Preço
            $table->decimal('vlr_tot', 12, 4)->nullable(); // Vlr Tot.
            $table->decimal('icms', 12, 4)->nullable();   // ICMS
            $table->decimal('ipi', 12, 4)->nullable();    // IPI
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pedido_itens');
    }
};
