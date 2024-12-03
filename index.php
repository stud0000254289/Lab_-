<?php

require 'vendor/autoload.php';
require 'database.php';

use SouleymanSidick\MyProject\Repositories\PostsRepository;
use SouleymanSidick\MyProject\Article;
use Ramsey\Uuid\Uuid;

// Подключение к базе данных
$db = getDatabaseConnection();

// Инициализация репозитория статей
$postsRepository = new PostsRepository($db);

// Массив с данными для новых статей
$articlesData = [
    ['author-uuid-1', 'Заголовок статьи 1', 'Текст статьи 1'],
    ['author-uuid-2', 'Заголовок статьи 2', 'Текст статьи 2'],
    ['author-uuid-3', 'Заголовок статьи 3', 'Текст статьи 3'],
    ['author-uuid-4', 'Заголовок статьи 4', 'Текст статьи 4'],
    ['author-uuid-5', 'Заголовок статьи 5', 'Текст статьи 5']
];

// Добавление статей в базу данных
foreach ($articlesData as $data) {
    $article = new Article(Uuid::uuid4()->toString(), $data[0], $data[1], $data[2]);
    $postsRepository->save($article);
    echo "Статья сохранена с UUID: " . $article->uuid . " и заголовком: " . $data[1] . "<br>";
}

// Получение и вывод всех статей
echo "<br>Сохраненные статьи:<br>";
$stmt = $db->query("SELECT title, text FROM posts");
$articles = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($articles as $article) {
    echo "Заголовок: " . htmlspecialchars($article['title'], ENT_QUOTES, 'UTF-8') . "<br>";
    echo "Текст: " . htmlspecialchars($article['text'], ENT_QUOTES, 'UTF-8') . "<br><br>";
}
