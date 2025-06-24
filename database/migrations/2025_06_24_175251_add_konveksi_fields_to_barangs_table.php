    <?php
    
    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;
    
    return new class extends Migration
    {
        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up()
        {
            Schema::table('barangs', function (Blueprint $table) {
                // Menambahkan kolom baru setelah kolom 'id'
                $table->string('kode_barang')->unique()->nullable()->after('id');
                $table->string('unit')->nullable()->after('kategori'); // pcs, roll, kg, dll.
                $table->string('status_barang')->default('Tersedia')->after('stok');
            });
        }
    
        /**
         * Reverse the migrations.
         *
         * @return void
         */
        public function down()
        {
            Schema::table('barangs', function (Blueprint $table) {
                $table->dropColumn('kode_barang');
                $table->dropColumn('unit');
                $table->dropColumn('status_barang');
            });
        }
    };
    