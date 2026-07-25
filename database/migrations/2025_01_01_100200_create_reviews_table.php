<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('establishment_id')->constrained()->cascadeOnDelete();
            $table->longText('raw_text');
            $table->longText('response_text')->nullable();
            $table->string('sentiment')->default('neutral');
            $table->string('language')->nullable();
            $table->boolean('is_flagged')->default(false);
            $table->string('status')->default('pending');
            $table->unsignedTinyInteger('rating')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('reviews'); }
};
