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
    Schema::create('evaluations', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('student_id'); // from users table
        $table->unsignedBigInteger('teacher_id'); // from teachers table
        $table->tinyInteger('criteria1'); // punctuality
        $table->tinyInteger('criteria2'); // knowledge
        $table->tinyInteger('criteria3'); // communication
        $table->text('feedback')->nullable();
        $table->timestamps();

        // Foreign keys
        $table->foreign('student_id')->references('id')->on('users')->onDelete('cascade');
        $table->foreign('teacher_id')->references('id')->on('teachers')->onDelete('cascade');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluations');
    }
};
