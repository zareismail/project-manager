<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Zareismail\ProjectManager\Helper;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(Helper::prefixTable('projects'), function (Blueprint $table) {
            $table->id();
            $table->naming();
            $table->auth(); 
            $table->auth('manager');
            $table->string('number');
            $table->double('coefficient')->default(0.00);
            $table->unsignedBigInteger('warning')->nullable();
            $table->foreignId('employer_id')->constrained(Helper::prefixTable('employers'));
            $table->date('start_date')->nullable();
            $table->date('finish_date')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(Helper::prefixTable('projects'));
    }
}
