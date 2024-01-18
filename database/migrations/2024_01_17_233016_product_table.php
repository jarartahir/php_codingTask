<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('Products', function (Blueprint $table){
            $table->integer('entity_id')->primary();
            $table->string('CategoryName');
            $table->string('sku');
            $table->string('name');
            $table->text('description');
            $table->text('shortdesc');
            $table->float('price', 10, 2);
            $table->string('link');
            $table->string('image');
            $table->string('Brand');
            $table->integer('Rating');
            $table->string('CaffeineType');
            $table->integer('Count');
            $table->string('Flavored');
            $table->string('Seasonal');
            $table->string('Instock');
            $table->integer('Facebook');
            $table->integer('IsKCup');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
