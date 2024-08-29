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
        Schema::create('letter_keywords', function (Blueprint $table) {
            $table->uuid('letter_id');
            $table->unsignedBigInteger('keyword_id');

            $table->primary(['letter_id', 'keyword_id']);
            $table->foreign('letter_id')->references('letter_id')->on('letters')->onDelete('cascade');
            $table->foreign('keyword_id')->references('id')->on('keywords')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('letter_keywords');
    }
};
