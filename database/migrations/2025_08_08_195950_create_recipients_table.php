<?php

use App\Models\Country;
use App\Models\Letter;
use App\Models\State;
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
        Schema::create('recipients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained(User::table);
            $table->foreignId('letter_id')->constrained(Letter::table);
            $table->string('name')->nullable();
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('address_line_1')->nullable();
            $table->string('address_line_2')->nullable();
            $table->json('variables')->nullable();
            $table->foreignId('country_id')->nullable()->constrained(Country::table);
            $table->foreignId('state_id')->nullable()->constrained(State::table);
            $table->string('postal_code')->nullable();
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
        Schema::dropIfExists('recipients');
    }
};
