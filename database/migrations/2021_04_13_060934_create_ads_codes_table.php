<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdsCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ads_codes', function (Blueprint $table) {
            $table->id();
            $table->string("placement");
            $table->enum("type", ["ADSENSE"])->default("ADSENSE");
            $table->text("code")->nullable(true);
            $table->boolean("isEnable")->default(false);
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
        Schema::dropIfExists('ads_codes');
    }
}
