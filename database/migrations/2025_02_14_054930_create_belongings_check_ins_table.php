<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBelongingsCheckInsTable extends Migration
{
    public function up()
    {
        Schema::create('belongings_check_ins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('visitor_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('type');
            $table->integer('quantity');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }
 
    public function down()
    {
        Schema::dropIfExists('belongings_check_ins');
    }
}
