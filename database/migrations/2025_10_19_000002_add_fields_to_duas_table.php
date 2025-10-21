<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('duas', function (Blueprint $table) {
            if (!Schema::hasColumn('duas', 'subsection')) {
                $table->string('subsection')->nullable()->index()->after('category');
            }
            if (!Schema::hasColumn('duas', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable()->after('tags');
                $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            }
            if (!Schema::hasColumn('duas', 'is_public')) {
                $table->boolean('is_public')->default(true)->after('user_id');
            }
        });
    }

    public function down()
    {
        Schema::table('duas', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn(['subsection', 'user_id', 'is_public']);
        });
    }
};
