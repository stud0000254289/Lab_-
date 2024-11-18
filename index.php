<?php

require 'vendor/autoload.php';
require 'database.php';

use SouleymanSidick\MyProject\Article;
use SouleymanSidick\MyProject\Repositories\PostsRepository;
use Ramsey\Uuid\Uuid;

// Подключение к базе данных
$db = getDatabaseConnection();

// Инициализация репозитория статей
$postsRepository = new PostsRepository($db);

// Массив с данными для новых статей
$articlesData = [
    ['Заголовок статьи 1', 'Текст статьи 1'],
    ['Заголовок статьи 2', 'Текст статьи 2'],
    ['Заголовок статьи 3', 'Текст статьи 3'],
    ['Заголовок статьи 4', 'Текст статьи 4'],
    ['Заголовок статьи 5', 'Текст статьи 5']
];

// Добавление статей в базу данных
foreach ($articlesData as $data) {
    $article = new Article(Uuid::uuid4()->toString(), $data[0], $data[1]);
    $postsRepository->save($article);
    echo "Статья сохранена с UUID: " . $article->uuid . " и заголовком: " . $data[0] . PHP_EOL;
}

// Получение и вывод всех статей
echo PHP_EOL . "Сохраненные статьи:" . PHP_EOL;
foreach ($articlesData as $data) {
    $retrievedArticle = $postsRepository->get(Uuid::fromString($article->uuid));
    if ($retrievedArticle) {
        echo "Заголовок: " . $retrievedArticle->title . PHP_EOL;
    } else {
        echo "Статья не найдена." . PHP_EOL;
    }
}


