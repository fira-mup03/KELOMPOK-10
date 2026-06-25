<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('treatment_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('booking_id')->nullable()->constrained('bookings')->onDelete('set null');
            $table->foreignId('doctor_id')->constrained('doctors')->onDelete('cascade');
            $table->date('treatment_date');
            $table->text('diagnosis')->comment('Diagnosa dokter');
            $table->text('treatment')->comment('Tindakan perawatan yang dilakukan');
            $table->text('prescription')->nullable()->comment('Resep obat (opsional)');
            $table->date('next_visit')->nullable()->comment('Saran kunjungan berikutnya');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('treatment_histories');
    }
};
