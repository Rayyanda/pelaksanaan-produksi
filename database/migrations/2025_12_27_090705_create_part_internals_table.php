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
        Schema::create('part_internals', function (Blueprint $table) {
            $table->id();
            $table->string('part_number')->unique();
            $table->integer('nointernal')->nullable();
            $table->string('part_name')->nullable();
            $table->string('matl_spec')->nullable();
            $table->decimal('matl_req', 15, 2)->nullable();
            $table->text('deoxidation')->nullable();
            $table->integer('comp_per_mould')->nullable();
            $table->integer('target_qty_waxing')->nullable();
            $table->integer('target_qty_mould_room')->nullable();
            $table->integer('target_qty_melting')->nullable();
            $table->integer('target_qty_heat_treatment')->nullable();
            $table->integer('target_qty_cut_off')->nullable();
            $table->integer('target_qty_finishing')->nullable();
            $table->integer('target_qty_machining')->nullable();
            $table->integer('target_qty_quality_control')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('part_internals');
    }
};
