<?php

use App\Models\enums\PaymentMethodEnum;
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
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users','id')->cascadeOnDelete();
            $table->foreignId('unit_id')->nullable()->constrained()->onDelete('cascade');
            $table->enum('payment_method', array_column(PaymentMethodEnum::cases(), 'value'))
                ->default(PaymentMethodEnum::CASH->value);
            $table->decimal('down_payment', 10, 2);;
            $table->date('payment_date')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
