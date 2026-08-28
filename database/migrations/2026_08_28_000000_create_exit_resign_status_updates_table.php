<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExitResignStatusUpdatesTable extends Migration
{
    public function up()
    {
        Schema::create('exit_resign_status_updates', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('exit_resign_id')->index();
            $table->string('status');
            $table->string('document');
            $table->string('original_name');
            $table->text('remarks')->nullable();
            $table->unsignedInteger('updated_by')->nullable()->index();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('exit_resign_status_updates');
    }
}
