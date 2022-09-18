<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {            
            $table->bigIncrements('id');
            $table->string('task_title')->nullable();
            $table->text('task_description')->nullable();
            $table->string('assign_by')->nullable();
            $table->string('task_progress')->nullable();
            $table->string('task_status')->nullable();
            $table->date("start_date")->nullable()->default(null);
            $table->date("end_date")->nullable()->default(null);
            $table->string('user_id')->nullable();
            $table->unsignedBigInteger('project_id')->unsigned();
            $table->foreign('project_id')->on('projects')->references('id')->onDelete('cascade');
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
        Schema::dropIfExists('tasks');
    }
}
