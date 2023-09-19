<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyDocsAndLinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_docs_and_links', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->integer('employee_id');
            $table->text('name');
            $table->text('description')->nullable();
            $table->enum('type', ['link', 'doc']);
            $table->text('upload')->nullable();
            $table->timestamps(6);
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
        Schema::dropIfExists('company_docs_and_links');
    }
}
