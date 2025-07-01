<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogEntriesTable extends Migration
{
    public function up()
    {
        Schema::create('log_entries', function (Blueprint $table) {
            $table->id();
            $table->string('level');
            $table->text('message');
            $table->timestamp('created_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('log_entries');
    }
}