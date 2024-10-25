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
        Schema::create('quotations', function (Blueprint $table) {
            $table->id();
            $table->string('title')->default('Quotation');
            $table->foreignId('client_id')->nullable()->constrained()->nullOnDelete();
            $table->string('cc')->nullable();
            $table->date('date');
            $table->string('no');
            $table->integer('tax')->default(0);
            $table->integer('termin')->default(0);
            $table->integer('maintenance')->default(0);
            $table->foreignId('payment_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotations');
    }
};
