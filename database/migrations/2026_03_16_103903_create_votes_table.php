<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVotesTable extends Migration
{
    public function up()
    {
        Schema::create('votes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('poll_id');
            $table->unsignedBigInteger('poll_option_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();

            $table->foreign('poll_id')->references('id')->on('polls')->onDelete('cascade');
            $table->foreign('poll_option_id')->references('id')->on('poll_options')->onDelete('cascade');

            $table->index(['poll_id', 'user_id']);
            $table->index(['poll_id', 'ip_address']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('votes');
    }
}