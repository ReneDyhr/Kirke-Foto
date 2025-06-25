<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('deaneries', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('diocese_id')
                ->constrained('dioceses')
                ->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->longText('placemark')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->comment('Provsti');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deaneries');
    }
};
