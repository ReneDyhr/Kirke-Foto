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
        Schema::create('churches', function (Blueprint $table): void {
            $table->bigIncrements('id');
            $table->string('url');
            $table->string('name');
            $table->longText('description');
            $table->string('seo_description');
            $table->string('seo_tags');
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            $table->foreignId('parish_id')->constrained()->onDelete('cascade');
            $table->tinyInteger('drone_approval');
            $table->tinyInteger('open_area');
            $table->tinyInteger('contact_later');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('churches');
    }
};
