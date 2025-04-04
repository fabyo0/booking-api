<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('apartments', function (Blueprint $table): void {
            $table->foreignId('apartment_type_id')
                ->nullable()
                ->after('id')
                ->constrained();
            $table->unsignedInteger('size')->nullable();
            $table->unsignedInteger('bathrooms')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('apartments', function (Blueprint $table): void {});
    }
};
