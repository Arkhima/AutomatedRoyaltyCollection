<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up(): void
{
    Schema::table('artists', function (Blueprint $table) {
        $table->text('bio')->nullable()->after('profile_image');
        $table->string('instagram')->nullable()->after('bio');
        $table->string('twitter')->nullable()->after('instagram');
    });
}

public function down(): void
{
    Schema::table('artists', function (Blueprint $table) {
        $table->dropColumn(['bio', 'instagram', 'twitter']);
    });
}

};
