<?php

use App\Models\Enums\UnitStatusEnum;
use App\Models\Enums\UnitTypeEnum;
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
        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->enum('unit_type', array_column(UnitTypeEnum::cases(), 'value'));
            $table->decimal('unit_area', 10, 2); // Allows decimal values like 120.50 sqm
            $table->enum('unit_status', array_column(UnitStatusEnum::cases(), 'value'));
            $table->integer('number_bedrooms')->unsigned();
            $table->integer('number_bathrooms')->unsigned();
            $table->date('expected_delivery_date')->nullable();
            $table->foreignId('location_id')->constrained()->cascadeOnDelete();
            $table->foreignId('developer_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('project_id')->nullable()->constrained('projects', 'id')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('units');
    }
};
