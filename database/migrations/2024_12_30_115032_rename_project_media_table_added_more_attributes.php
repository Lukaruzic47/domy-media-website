<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::rename('project_media', 'media');
        Schema::table('media', function (Blueprint $table) {
            $table->string('file_name')->nullable()->after('path');
            $table->string('mime_type')->nullable()->after('file_name');
            $table->string('size')->nullable()->after('mime_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::table('media', function (Blueprint $table) {
            $table->dropColumn(['file_name', 'mime_type', 'size']);
        });
        Schema::rename('media', 'project_media');
    }
};
