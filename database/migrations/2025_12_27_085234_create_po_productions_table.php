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
        Schema::create('po_productions', function (Blueprint $table) {
            $table->id();
            $table->string('po_number')->unique();
            $table->json('po_snapshot')->nullable();
            $table->integer('quantity');
            $table->date('due_date')->nullable();
            $table->string('po_source')->nullable();
            $table->enum('status', ['pending', 'scheduled', 'on_production', 'completed', 'cancelled'])->default('pending');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('po_productions');
    }
};
