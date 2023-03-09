<?php

namespace Spanjaan\ContactForm\Updates;

use Schema;
use Winter\Storm\Database\Schema\Blueprint;
use Winter\Storm\Database\Updates\Migration;

class CreateSpanjaanContactform extends Migration
{
    public function up()
    {
        Schema::create('spanjaan_contactform', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('subject')->nullable();
            $table->text('message')->nullable();
            $table->boolean('is_new')->default(1);
            $table->timestamps();
            $table->boolean('is_replied')->default(0);
        });
    }

    public function down()
    {
        Schema::dropIfExists('spanjaan_contactform');
    }
}
