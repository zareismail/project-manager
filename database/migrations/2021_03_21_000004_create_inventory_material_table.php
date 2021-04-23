<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Zareismail\ProjectManager\Helper;

class CreateInventoryMaterialTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(Helper::prefixTable('inventory_material'), function (Blueprint $table) {
            $table->id(); 
            $table->foreignId('inventory_id')->constrained(Helper::prefixTable('inventories')); 
            $table->foreignId('material_id')->constrained(Helper::prefixTable('materials'));  
            $table->foreignId('unit_id')->constrained('keil_units');  
            $table->integer('value')->default(1);
            $table->integer('stock')->default(0);
            $table->longPrice()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(Helper::prefixTable('inventory_material'));
    }
}
