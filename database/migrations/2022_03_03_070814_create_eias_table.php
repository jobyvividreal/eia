<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEiasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eias', function (Blueprint $table) {
            $table->id();
            $table->text('document_number');
            $table->timestamp('date_of_entry')->nullable();
            $table->text('title');
            $table->longText('brief_description');
            $table->integer('uploaded_by');
            $table->integer('created_by');
            $table->integer('document_type');
            $table->longText('comment');
            $table->tinyInteger('status')->default('0');
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
        Schema::dropIfExists('eias');
    }
}
