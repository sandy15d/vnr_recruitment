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
        Schema::create('exam_masters', function (Blueprint $table) {
            $table->id();
            $table->string('exam_name');
            $table->string('test_paper');
            $table->integer('time');
            $table->integer('time_reminder');
            $table->integer('max_alert');
            $table->longText('instruction');
            $table->enum('status',['A','D'])->default('A');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_masters');
    }
};
