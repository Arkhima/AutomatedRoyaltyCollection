<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTracksTable extends Migration
{
    public function up()
    {
        Schema::create('tracks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('isrc_code')->nullable(); // ✅ NEW FIELD
            $table->unsignedBigInteger('artist_id');
            $table->decimal('rate_per_stream', 8, 2);
            $table->string('file_path')->nullable();
            $table->timestamps();

            $table->foreign('artist_id')
                  ->references('id')
                  ->on('artists')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('tracks');
    }
}
