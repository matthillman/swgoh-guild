<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Member extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('members', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('guild_id');
            $table->string('name');
            $table->string('slug')->unique();
            $table->unsignedInteger('power');
            $table->unsignedInteger('character_power');
            $table->unsignedInteger('ship_power');
            $table->timestamps();

            $table->foreign('guild_id')
                ->references('id')->on('guilds')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('members');
    }
}