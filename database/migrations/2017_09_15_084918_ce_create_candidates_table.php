<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CeCreateCandidatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('candidates', function (Blueprint $table) {
            $table->increments('id');
            $table->string('full_name');
            $table->string('link');
            $table->tinyInteger('status')->default(0);
            $table->string('company_name')->nullable();
            $table->string('position')->nullable();
            $table->text('contact_details')->nullable();
            $table->text('client_comment')->nullable();
            $table->text('tr_client_comment')->nullable();
            $table->text('our_comment')->nullable();
            $table->integer('added_by')->unsigned()->nullable();
            $table->string('source')->default('platform');

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('added_by')->references('id')->on('ce_users');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('candidates');
    }
}
