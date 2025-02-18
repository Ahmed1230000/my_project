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
        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->enum('unit_type', [
                'apartment',
                'villa',
                'townhouse',
                'studio',
                'penthouse',
                'duplex',
                'office_space',
                'retail_store',
                'warehouse',
                'serviced_apartment'
            ]);
            $table->decimal('unit_area', 10, 2); // Allows decimal values like 120.50 sqm
            $table->enum('unit_status', ['available', 'sold', 'rented', 'under_construction']);
            $table->integer('number_bedrooms')->unsigned();
            $table->integer('number_bathrooms')->unsigned();
            $table->date('expected_delivery_date')->nullable();
            $table->foreignId('location_id')->constrained()->cascadeOnDelete();
            $table->foreignId('developer_id')->nullable()->constrained()->cascadeOnDelete();
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
