<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('seo_contents', function (Blueprint $table) {
            $table->string('name')->nullable()->change();
            $table->string('meta_title')->nullable()->change();
            $table->text('meta_description')->nullable()->change();
            $table->text('meta_keywords')->nullable()->change();
            $table->string('page_slug')->nullable()->change();
            $table->longText('content')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('seo_contents')
            ->whereNull('name')
            ->update(['name' => '']);

        DB::table('seo_contents')
            ->whereNull('meta_title')
            ->update(['meta_title' => '']);

        DB::table('seo_contents')
            ->whereNull('meta_description')
            ->update(['meta_description' => '']);

        DB::table('seo_contents')
            ->whereNull('meta_keywords')
            ->update(['meta_keywords' => '']);

        DB::table('seo_contents')
            ->whereNull('page_slug')
            ->pluck('id')
            ->each(function ($id) {
                DB::table('seo_contents')
                    ->where('id', $id)
                    ->update(['page_slug' => 'seo-content-' . $id]);
            });

        Schema::table('seo_contents', function (Blueprint $table) {
            $table->string('name')->nullable(false)->change();
            $table->string('meta_title')->nullable(false)->change();
            $table->text('meta_description')->nullable(false)->change();
            $table->text('meta_keywords')->nullable(false)->change();
            $table->string('page_slug')->nullable(false)->change();
            $table->longText('content')->nullable()->change();
        });
    }
};
