<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnSourceIdToArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('articles', function (Blueprint $table) {
            //
            if (!Schema::hasColumn('articles', 'source_id')) {

                $table->integer('source_id')
                    ->after('category_id')
                    ->nullable();

                $table->foreign('source_id')
                    ->references('id')
                    ->on('clients');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('articles', function (Blueprint $table) {
            //
            if (Schema::hasColumn('articles', 'source_id')) {
                $table->dropColumn('source_id');
            }
        });
    }
}
