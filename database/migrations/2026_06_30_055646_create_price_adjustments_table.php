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
            Schema::create('price_adjustments', function (Blueprint $table) {
                $table->id();

                $table->foreignId('fuel_type_id')
                    ->constrained()
                    ->cascadeOnDelete();

                $table->decimal('adjustment', 5, 2);

                $table->enum('direction', ['increase', 'decrease']);

                $table->date('effective_date');

                $table->text('announcement')->nullable();

                $table->timestamps();
            });
        }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('price_adjustments');
    }
};
