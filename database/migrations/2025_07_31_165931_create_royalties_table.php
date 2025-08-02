<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoyaltiesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('royalties', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('artist_id');
            $table->unsignedBigInteger('track_id');
            $table->date('calculation_date');
            $table->unsignedBigInteger('total_streams')->default(0);
            $table->decimal('rate_per_stream', 8, 4)->default(0);
            $table->decimal('total_royalty', 12, 2)->default(0);
            $table->timestamps();

            $table->foreign('artist_id')->references('id')->on('artists')->onDelete('cascade');
            $table->foreign('track_id')->references('id')->on('tracks')->onDelete('cascade');

            $table->unique(['artist_id', 'track_id', 'calculation_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('royalties');
    }
}
