<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePollsTable extends Migration
{
    public function up()
    {
        Schema::create('polls', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->string('question');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('admin_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('polls');
    }
}