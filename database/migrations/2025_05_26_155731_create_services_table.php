<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('space_service_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('space_id')->constrained('spaces');
            $table->foreignId('service_id')->constrained('services');
            $table->decimal('price', 8, 2);
            $table->decimal('commission_percentage', 5, 2);
            $table->timestamps();

            $table->unique(['space_id', 'service_id']);
        });        

        Schema::create('service_sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('space_id')->constrained('spaces');
            $table->foreignId('service_id')->constrained('services');
            $table->decimal('price_charged', 8, 2);
            $table->decimal('commission_percentage', 5, 2);
            $table->decimal('commission_value', 8, 2);
            $table->timestamp('performed_at');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('service_sales');
        Schema::dropIfExists('space_service_prices');
        Schema::dropIfExists('services');
    }
};
