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
        Schema::create('parishes', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('deanery_id')
                ->constrained('deaneries')
                ->onDelete('cascade');
            $table->string('url');
            $table->string('name');
            $table->text('description')->nullable();
            $table->longText('placemark')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('parishes');
        Schema::enableForeignKeyConstraints();
    }
};
