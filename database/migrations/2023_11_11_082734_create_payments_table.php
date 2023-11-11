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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('id_pembayaran')->nullable();
            $table->string('collection_id')->nullable();
            $table->string('paid')->nullable();
            $table->string('state')->nullable();
            $table->string('amount')->nullable();
            $table->string('paid_amount')->nullable();
            $table->string('due_at')->nullable();
            $table->string('email')->nullable();
            $table->string('mobile')->nullable();
            $table->string('name')->nullable();
            $table->string('url')->nullable();
            $table->string('reference_1_label')->nullable();
            $table->string('reference_1')->nullable();
            $table->string('reference_2_label')->nullable();
            $table->string('reference_2')->nullable();
            $table->string('redirect_url')->nullable();
            $table->string('callback_url')->nullable();
            $table->string('description')->nullable();
            $table->string('paid_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
