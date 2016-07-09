<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class Initial extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('config', function (Blueprint $table) {
            $table->mediumText('key')->unique();
            $table->primary('key');
            $table->longText('value');
            $table->softDeletes();
        });

        Schema::create('categories', function (Blueprint $table) {
            $table->increments('id');
            $table->mediumText('slug')->unique();
            $table->mediumText('name');
            $table->mediumText('template')->nullable();
            $table->timestamps();
        });

        Schema::create('pages', function (Blueprint $table) {
            $table->increments('id');
            $table->mediumText('slug')->unique();
            $table->mediumText('title');
            $table->timestamps();
            $table->timestamp('published_on');
            $table->text('body')->nullable();
            $table->softDeletes();
        });

        Schema::create('tags', function (Blueprint $table) {
            $table->increments('id');
            $table->mediumText('slug')->unique();
            $table->mediumText('name');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('files', function (Blueprint $table) {
            $table->increments('id');
            $table->mediumText('slug')->unique();
            $table->mediumText('title')->nullable();
            $table->string('path');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('images', function (Blueprint $table) {
            $table->increments('id');
            $table->mediumText('slug')->unique();
            $table->mediumText('title')->nullable();
            $table->string('path');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('posts', function (Blueprint $table) {
            $table->increments('id');
            $table->mediumText('slug')->unique();
            $table->mediumText('title')->nullable();
            $table->timestamp('published_on');
            $table->text('desc')->nullable();
            $table->text('body')->nullable();
            $table->integer('category')->unsigned();
            $table->integer('image')->unsigned()->nullable();
            $table->foreign('category')->references('id')->on('categories');
            $table->foreign('image')->references('id')->on('images');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('posts_tags', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('post')->unsigned();
            $table->integer('tag')->unsigned();
            $table->foreign('post')->references('id')->on('posts');
            $table->foreign('tag')->references('id')->on('tags');
        });

        Schema::create('post_meta', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('post')->unsigned();
            $table->foreign('post')->references('id')->on('posts');
            $table->mediumText('key');
            $table->longText('value');
            $table->softDeletes();
        });

        Schema::create('post_links', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('post')->unsigned();
            $table->foreign('post')->references('id')->on('posts');
            $table->mediumText('name');
            $table->longText('url');
            $table->mediumText('css_class')->nullable();
            $table->softDeletes();
        });

        Schema::create('links', function (Blueprint $table) {
            $table->increments('id');
            $table->mediumText('name');
            $table->longText('desc')->nullable();
            $table->longText('url');
            $table->integer('sort_index')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('user_meta', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user')->unsigned();
            $table->foreign('user')->references('id')->on('user')->onDelete('cascade');
            $table->mediumText('key');
            $table->longText('value');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('config');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('pages');
        Schema::dropIfExists('tags');
        Schema::dropIfExists('files');
        Schema::dropIfExists('images');
        Schema::dropIfExists('posts');
        Schema::dropIfExists('posts_tags');
        Schema::dropIfExists('post_meta');
        Schema::dropIfExists('post_links');
        Schema::dropIfExists('links');
        Schema::dropIfExists('user_meta');
    }
}
