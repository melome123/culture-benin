<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use function Symfony\Component\Clock\now;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('regions', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->string('nom');
            $table->longText('description');
            $table->integer('superficie');
            $table->integer('population');
            $table->string('localisation')->nullable();
        });
        Schema::create('langues', function (Blueprint $table) {
           $table->id()->autoIncrement();
            $table->string('nomlang');
            $table->longText('description');
            $table->string('codelang');
        });
        Schema::create('roles', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->string('nomrole');
        });
        Schema::create('typecontenus', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->string('nomtypec');
        });
        Schema::create('typemedias', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->string('nomtypem');
        });
        Schema::create('users_ban', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->string('nom');
            $table->string('prenom');
            $table->string('email')->unique();
            $table->foreignId('user_id');
            $table->date('day_ban');
        });
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('nom')->index();
            $table->string('prenom')->index();
            $table->string('password')->index();
            $table->date('date_naissance');
            $table->timestamp('created_at')->useCurrent();
            $table->string('statut')->default('active');
            $table->foreignId('langue_id');
            $table->foreignId('region_id');
            $table->foreignId('role_id');
        });
        Schema::create('contenus', function (Blueprint $table) {
           $table->id()->autoIncrement();
            $table->string('titre');
            $table->longText('texte');
            $table->timestamp('created_at')->useCurrent();
            $table->date('date_valid')->nullable();
            $table->foreignId('langue_id');
            $table->foreignId('region_id');
            $table->foreignId('contenu_id');
            $table->foreignId('typecontenu_id');
            $table->foreignId('user_id');
            $table->string('statut')->default('en attente');
        });
        Schema::create('medias', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->string('chemin');
            $table->longText('description');
            $table->foreignId('typemedia_id');
            $table->foreignId('contenu_id');
        });
         Schema::create('parlers', function (Blueprint $table) {
            $table->foreignId('langue_id');
            $table->foreignId('region_id');
            $table->primary(['langue_id','region_id']);
        });
            Schema::create('commentaires', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->longText('texte');
            $table->tinyInteger('note');
            $table->string('statut')->default('en attente');
            $table->date('published_at');
            $table->foreignId('contenu_id');
            $table->foreignId('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('regions');
        Schema::dropIfExists('users');
        Schema::dropIfExists('user_bans');
        Schema::dropIfExists('contenus');
        Schema::dropIfExists('roles');
        Schema::dropIfExists('medias');
        Schema::dropIfExists('typeroles');
        Schema::dropIfExists('typemedias');
        Schema::dropIfExists('typecontenus');
        Schema::dropIfExists('commentaires');
        Schema::dropIfExists('langues');
        Schema::dropIfExists('parlers');
    }
};
