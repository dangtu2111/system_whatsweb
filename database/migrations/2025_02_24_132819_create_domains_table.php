<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDomainsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('domains', function (Blueprint $table) {
            $table->increments('id'); // Khóa chính tự động tăng
            $table->integer('user_id')->nullable(); // ID người dùng sở hữu domain
            $table->string('slug')->unique(); // Định danh duy nhất cho domain
            $table->boolean('is_active')->default(true); // Trạng thái hoạt động
            $table->timestamps(); // created_at & updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('domains');
    }
}
