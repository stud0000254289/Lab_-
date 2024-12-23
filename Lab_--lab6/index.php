<?php

require 'vendor/autoload.php';
require 'database.php';

use SouleymanSidick\MyProject\Repositories\PostsRepository;
use SouleymanSidick\MyProject\Repositories\CommentsRepository;
use SouleymanSidick\MyProject\Repositories\LikesRepository;
use SouleymanSidick\MyProject\Article;
use SouleymanSidick\MyProject\Comment;
use SouleymanSidick\MyProject\Like;
use Ramsey\Uuid\Uuid;

// Подключение к базе данных
$db = getDatabaseConnection();

// Инициализация репозиториев
$postsRepository = new PostsRepository($db);
$commentsRepository = new CommentsRepository($db);
$likesRepository = new LikesRepository($db);

// Массив с данными для новых статей
$articlesData = [
    ['author-uuid-1', 'Заголовок статьи 1', 'Текст статьи 1'],
    ['author-uuid-2', 'Заголовок статьи 2', 'Текст статьи 2'],
    ['author-uuid-3', 'Заголовок статьи 3', 'Текст статьи 3'],
    ['author-uuid-4', 'Заголовок статьи 4', 'Текст статьи 4'],
    ['author-uuid-5', 'Заголовок статьи 5', 'Текст статьи 5']
];

// Добавление статей, если их еще нет
foreach ($articlesData as $data) {
    $stmt = $db->prepare("SELECT COUNT(*) FROM posts WHERE title = :title AND author_uuid = :author_uuid");
    $stmt->execute([':title' => $data[1], ':author_uuid' => $data[0]]);
    $exists = $stmt->fetchColumn() > 0;

    if (!$exists) {
        $article = new Article(Uuid::uuid4()->toString(), $data[0], $data[1], $data[2]);
        $postsRepository->save($article);
        echo "Статья сохранена с UUID: " . $article->uuid . " и заголовком: " . $data[1] . "<br>";
    }
}

// Обработка запросов
$requestMethod = $_SERVER['REQUEST_METHOD'];
$requestUri = $_SERVER['REQUEST_URI'];

if ($requestMethod === 'POST' && strpos($requestUri, '/posts/comment') === 0) {
    $inputData = json_decode(file_get_contents('php://input'), true);

    // Вывод входных данных для отладки
    var_dump($inputData);

    if (!isset($inputData['author_uuid'], $inputData['post_uuid'], $inputData['text'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing required fields: author_uuid, post_uuid, text']);
        exit;
    }

    try {
        // Проверка на уникальность комментария
        $stmt = $db->prepare("SELECT COUNT(*) FROM comments WHERE text = :text AND post_uuid = :post_uuid AND author_uuid = :author_uuid");
        $stmt->execute([
            ':text' => $inputData['text'],
            ':post_uuid' => $inputData['post_uuid'],
            ':author_uuid' => $inputData['author_uuid']
        ]);
        if ($stmt->fetchColumn() > 0) {
            throw new Exception('Duplicate comment is not allowed');
        }

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
} elseif ($requestMethod === 'POST' && strpos($requestUri, '/posts/like') === 0) {
    $inputData = json_decode(file_get_contents('php://input'), true);

    if (!isset($inputData['post_uuid'], $inputData['user_uuid'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing required fields: post_uuid, user_uuid']);
        exit;
    }

    try {
        // Проверка на существующий лайк
        $stmt = $db->prepare("SELECT COUNT(*) FROM likes WHERE post_uuid = :post_uuid AND user_uuid = :user_uuid");
        $stmt->execute([
            ':post_uuid' => $inputData['post_uuid'],
            ':user_uuid' => $inputData['user_uuid']
        ]);
        if ($stmt->fetchColumn() > 0) {
            throw new Exception('User has already liked this post');
        }

        $like = new Like(
            Uuid::uuid4()->toString(),
            $inputData['post_uuid'],
            $inputData['user_uuid']
        );
        $likesRepository->save($like);

        echo json_encode(['message' => 'Like added successfully']);
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
    // Вывод всех статей с комментариями и лайками
    echo "<br>Сохраненные статьи с комментариями:<br>";
    $stmt = $db->query("
        SELECT p.uuid AS post_uuid, p.title, p.text, 
               c.text AS comment_text, c.author_uuid AS comment_author,
               COUNT(l.uuid) AS likes_count
        FROM posts p
        LEFT JOIN comments c ON c.post_uuid = p.uuid
        LEFT JOIN likes l ON l.post_uuid = p.uuid
        GROUP BY p.uuid, c.text
    ");
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($results as $row) {
        echo "<br>Заголовок: " . htmlspecialchars($row['title'], ENT_QUOTES, 'UTF-8') . "<br>";
        echo "Текст: " . htmlspecialchars($row['text'], ENT_QUOTES, 'UTF-8') . "<br>";
        if ($row['comment_text']) {
            echo "Комментарий: " . htmlspecialchars($row['comment_text'], ENT_QUOTES, 'UTF-8') . " (Автор: " . htmlspecialchars($row['comment_author'], ENT_QUOTES, 'UTF-8') . ")<br>";
        }
        echo "Лайков: " . $row['likes_count'] . "<br><br>";
    }
}
