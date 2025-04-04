<?php

declare(strict_types=1);

use App\Models\Apartment;
use App\Models\Facility;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('apartment_facility', function (Blueprint $table): void {
            $table->foreignIdFor(Apartment::class)->constrained();
            $table->foreignIdFor(Facility::class)->constrained();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('apartment_facility');
    }
};
