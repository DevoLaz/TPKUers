    <?php
    
    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;
    
    return new class extends Migration
    {
        public function up()
        {
            Schema::create('suppliers', function (Blueprint $table) {
                $table->id();
                $table->string('nama_supplier');
                $table->string('kontak_person')->nullable();
                $table->string('no_telepon')->nullable();
                $table->text('alamat')->nullable();
                $table->timestamps();
            });
        }
    
        public function down()
        {
            Schema::dropIfExists('suppliers');
        }
    };
    