<?php

require 'vendor/autoload.php';
require 'database.php';

use SouleymanSidick\MyProject\Repositories\PostsRepository;
use SouleymanSidick\MyProject\Repositories\CommentsRepository;
use SouleymanSidick\MyProject\Article;
use SouleymanSidick\MyProject\Comment;
use Ramsey\Uuid\Uuid;

// Подключение к базе данных
$db = getDatabaseConnection();

// Очистка таблицы статей перед добавлением новых записей
$db->exec("DELETE FROM posts");
$db->exec("DELETE FROM comments"); // Очистка таблицы комментариев (если нужно)

// Инициализация репозиториев
$postsRepository = new PostsRepository($db);
$commentsRepository = new CommentsRepository($db);

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

// Обработка запросов
$requestMethod = $_SERVER['REQUEST_METHOD'];
$requestUri = $_SERVER['REQUEST_URI'];

if ($requestMethod === 'POST' && strpos($requestUri, '/posts/comment') === 0) {
    $inputData = json_decode(file_get_contents('php://input'), true);

    if (!isset($inputData['author_uuid'], $inputData['post_uuid'], $inputData['text'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing required fields: author_uuid, post_uuid, text']);
        exit;
    }

    try {
        $comment = new Comment(
            Uuid::uuid4()->toString(),
            $inputData['post_uuid'],
            $inputData['author_uuid'],
            $inputData['text']
        );
        $commentsRepository->save($comment);
        echo json_encode(['message' => 'Comment added successfully']);
    } catch (Exception $e) {
        http_response_code(400);
        echo json_encode(['error' => $e->getMessage()]);
    }

} elseif ($requestMethod === 'DELETE' && strpos($requestUri, '/posts') === 0) {
    // Обработка удаления статьи
    parse_str(parse_url($requestUri, PHP_URL_QUERY), $queryParams);

    if (empty($queryParams['uuid'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing required field: uuid']);
        exit;
    }

    try {
        $postUuid = $queryParams['uuid'];
        $db->prepare("DELETE FROM posts WHERE uuid = :uuid")->execute([':uuid' => $postUuid]);
        echo json_encode(['message' => 'Post deleted successfully']);
    } catch (Exception $e) {
        http_response_code(400);
        echo json_encode(['error' => $e->getMessage()]);
    }
} else {
    // Вывод всех статей с комментариями
    echo "<br>Сохраненные статьи с комментариями:<br>";
    $stmt = $db->query("SELECT * FROM posts");
    $articles = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($articles as $article) {
        echo "<br>Заголовок: " . htmlspecialchars($article['title'], ENT_QUOTES, 'UTF-8') . "<br>";
        echo "Текст: " . htmlspecialchars($article['text'], ENT_QUOTES, 'UTF-8') . "<br>";

        // Вывод комментариев для статьи
        $postUuid = $article['uuid'];
        $commentStmt = $db->prepare("SELECT * FROM comments WHERE post_uuid = :post_uuid");
        $commentStmt->execute([':post_uuid' => $postUuid]);
        $comments = $commentStmt->fetchAll(PDO::FETCH_ASSOC);

        if ($comments) {
            echo "Комментарии:<br>";
            foreach ($comments as $comment) {
                echo "- " . htmlspecialchars($comment['text'], ENT_QUOTES, 'UTF-8') . " (Автор: " . htmlspecialchars($comment['author_uuid'], ENT_QUOTES, 'UTF-8') . ")<br>";
            }
        } else {
            echo "Нет комментариев.<br>";
        }

        echo "<br>";
    }
}







