<?php

require 'vendor/autoload.php';

use Faker\Factory;
use SouleymanSidick\MyProject\User;
use SouleymanSidick\MyProject\Article;
use SouleymanSidick\MyProject\Comment;

// Создаем экземпляр Faker
$faker = Factory::create();

// Создаем пользователя
$user = new User(1, $faker->firstName, $faker->lastName);
echo "User: {$user->firstName} {$user->lastName}\n";

// Создаем статью
$article = new Article(1, $user->id, $faker->sentence, $faker->paragraph);
echo "Article: {$article->title}\n";

// Создаем комментарий
$comment = new Comment(1, $user->id, $article->id, $faker->sentence);
echo "Comment: {$comment->content}\n";
