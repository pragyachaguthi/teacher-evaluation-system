<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('program_semester_codes', function (Blueprint $table) {
            $table->id();
            $table->string('faculty');
            $table->string('program');
            $table->integer('semester');
            $table->string('code')->unique();  // example: BIM-5-2025
            $table->timestamps();
        });
    }




    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('program_semester_codes');
    }
};
