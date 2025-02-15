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
         Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('body'); 
            $table->string('file'); 
            $table->foreignId('user_id')->constrained();
            // $table->timestamp('created_at')->nullable();
            // $table->timestamp('updated_at')->nullable()
            $table->timestamps(); ;
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
