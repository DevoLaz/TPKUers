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
        Schema::create('aset_tetaps', function (Blueprint $table) {
            $table->id();
            $table->string('nama_aset');
            $table->text('deskripsi')->nullable();
            $table->string('kategori');
            $table->date('tanggal_perolehan');
            $table->decimal('harga_perolehan', 15, 2);
            $table->integer('masa_manfaat'); // Dalam tahun
            $table->decimal('nilai_residu', 15, 2)->default(0);
            $table->string('metode_penyusutan')->default('Garis Lurus');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aset_tetaps');
    }
};