<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommentsTable extends Migration
{
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();  // ID комментария
            $table->text('content');  // Текст комментария
            $table->foreignId('user_id')->constrained()->onDelete('cascade');  // Пользователь, который оставил комментарий
            $table->foreignId('bug_id')->constrained()->onDelete('cascade');  // Ошибка, к которой привязан комментарий
            $table->timestamps();  // Время создания и обновления комментария
        });
    }

    public function down()
    {
        Schema::dropIfExists('comments');
    }
}
