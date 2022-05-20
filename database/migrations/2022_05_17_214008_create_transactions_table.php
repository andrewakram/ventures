<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->nullable()->references('id')->on('categories')->onDelete('restrict');
            $table->foreignId('sub_category_id')->nullable()->references('id')->on('sub_categories')->onDelete('restrict');
            $table->foreignId('user_id')->nullable()->references('id')->on('users')->onDelete('restrict');
            $table->enum('status',["paid","outstanding","overdue"])->default('outstanding');
            $table->string('amount')->nullable();
            $table->string('due_on')->nullable();
            $table->string('vat')->nullable();
            $table->tinyInteger('is_vat_inclusive')->default(0);

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
        Schema::dropIfExists('transactions');
    }
}
