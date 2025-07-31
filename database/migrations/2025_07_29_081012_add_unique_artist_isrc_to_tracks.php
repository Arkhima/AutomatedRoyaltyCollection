<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends \Illuminate\Database\Migrations\Migration {
    public function up()
    {
        Schema::table('tracks', function (Blueprint $table) {
            $table->unique(['artist_id', 'isrc_code'], 'artist_isrc_unique');
        });
    }

    public function down()
    {
        Schema::table('tracks', function (Blueprint $table) {
            $table->dropUnique('artist_isrc_unique');
        });
    }
};
