<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->text('title')->after('id')->nullable();
            $table->date('date_of_creation')->after('title')->nullable();
            $table->bigInteger('company_id')->after('date_of_creation')->nullable();
            $table->text('category')->after('company_id')->nullable();
            $table->text('type')->after('category')->nullable();
            $table->string('total_budget')->after('type')->nullable();
            $table->tinyInteger('status')->after('total_budget')->default('1');
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
        Schema::table('projects', function (Blueprint $table) {
            //
        });
    }
}
