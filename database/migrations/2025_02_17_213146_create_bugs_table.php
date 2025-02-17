<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('bugs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->enum('severity', ['low', 'medium', 'high', 'critical']);
            $table->enum('priority', ['low', 'normal', 'high']);
            $table->enum('status', ['new', 'in_progress', 'testing', 'closed'])->default('new');
            $table->text('steps_to_reproduce')->nullable();
            $table->text('environment_info')->nullable();
            $table->json('attachments')->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); 
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('bugs');
    }
};
