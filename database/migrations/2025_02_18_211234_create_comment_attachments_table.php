<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommentAttachmentsTable extends Migration
{
    public function up()
    {
        Schema::create('comment_attachments', function (Blueprint $table) {
            $table->id();  // ID вложения
            $table->foreignId('comment_id')->constrained()->onDelete('cascade');  // Комментарий, к которому привязано вложение
            $table->string('path');  // Путь к файлу
            $table->timestamps();  // Время создания и обновления вложения
        });
    }

    public function down()
    {
        Schema::dropIfExists('comment_attachments');
    }
}
