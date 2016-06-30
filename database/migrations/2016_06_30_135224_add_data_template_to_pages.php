<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDataTemplateToPages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('pages', function ($table) {
            $table->json('data')->nullable();
            $table->string('template')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('pages', function ($table) {
            $table->dropColumn('data');
            $table->dropColumn('template');
        });
    }
}
