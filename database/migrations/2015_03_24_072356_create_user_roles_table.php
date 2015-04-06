<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserRolesTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('user_roles', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->length(10)->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->integer('role_id')->length(10)->unsigned();
            $table->foreign('role_id')->references('id')->on('roles');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::drop('user_roles');
    }

}
