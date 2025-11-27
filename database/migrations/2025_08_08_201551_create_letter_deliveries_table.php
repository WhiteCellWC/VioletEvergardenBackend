<?php

use App\Models\DeliveryOption;
use App\Models\DeliveryTier;
use App\Models\Recipient;
use App\Models\User;
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
        Schema::create('letter_deliveries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('recipient_id')->constrained(Recipient::table);
            $table->foreignId('delivery_option_id')->constrained(DeliveryOption::table);
            $table->foreignId('delivery_tier_id')->constrained(DeliveryTier::table);
            $table->decimal('delivery_cost');
            $table->string('tracking_number')->nullable();
            $table->enum('delivery_status', ['pending', 'delivered']);
            $table->datetime('scheduled_at');
            $table->datetime('shipped_at')->nullable();
            $table->datetime('delivered_at')->nullable();
            $table->bigInteger('version')->default(1);
            $table->foreignId('created_by')->nullable()->constrained(User::table);
            $table->foreignId('updated_by')->nullable()->constrained(User::table);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('letter_deliveries');
    }
};
