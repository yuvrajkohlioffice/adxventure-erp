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
        Schema::create('task', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('name');
            $table->tinyInteger('attachments_needed')->default('0');
            $table->tinyInteger('remark_needed')->default('0');
            $table->dateTime('deadline');
            $table->dateTime('estimated_time');
            $table->tinyInteger('status')->default('1');
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task');
    }
};
