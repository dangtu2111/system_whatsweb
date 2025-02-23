<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDestinationUrlsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('destination_urls', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->nullable();
            $table->string('slug')->unique();
            $table->string('phone_number');
            $table->string('phone_code')->nullable();
            $table->text('content')->nullable();
            $table->text('url')->nullable();
            $table->integer('hit')->default(0);
            $table->string('type')->default('WHATSAPP');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('destination_urls');
    }
}
