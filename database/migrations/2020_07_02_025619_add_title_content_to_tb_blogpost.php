<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTitleContentToTbBlogpost extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tb_blogposts', function (Blueprint $table) {
            $table->string('title')->default('');
            $table->text('content')->default('');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tb_blogposts', function (Blueprint $table) {
            $table->dropColumn(['title', 'content']);
        });
    }
}
