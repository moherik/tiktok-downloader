<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('videos', function (Blueprint $table) {
            $table->id();
            $table->text("tiktok_url");
            $table->string("desc");
            $table->string("author_id");
            $table->string("author_nickname");
            $table->string("author_username");
            $table->string("author_avatar");
            $table->text("signature");
            $table->text("vid_cover_url");
            $table->text("vid_play_url");
            $table->text("vid_download_url");
            $table->string("vid_created_at");
            $table->string("mus_title");
            $table->string("mus_author");
            $table->text("mus_play_url");
            $table->text("mus_cover_url");
            $table->string("digg_count");
            $table->string("share_count");
            $table->string("comment_count");
            $table->string("play_count");
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
        Schema::dropIfExists('videos');
    }
}
