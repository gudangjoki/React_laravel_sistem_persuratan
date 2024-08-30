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
        Schema::create('letters', function (Blueprint $table) {
            $table->uuid('letter_id')->primary();
            // $table->string('letter_number');
            $table->string('letter_title');
            $table->string('letter_path');
            $table->unsignedBigInteger('letter_id_type');
            $table->string('email');

            $table->foreign('letter_id_type')->references("id")->on("letter_types")->onDelete("cascade");
            $table->foreign('email')->references('email')->on('users')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('letters');
    }
};
