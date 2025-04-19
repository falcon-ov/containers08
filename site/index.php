<?php
require_once __DIR__ . '/modules/database.php';
require_once __DIR__ . '/modules/page.php';
require_once __DIR__ . '/config.php';

$db = new Database($config["db"]["path"]);
$page = new Page(__DIR__ . '/templates/index.tpl');

// Получаем ID страницы из GET-запроса, по умолчанию 1
$pageId = isset($_GET['page']) ? (int)$_GET['page'] : 1;

// Читаем данные из базы
$data = $db->Read("page", $pageId);

// Если данные не найдены, возвращаем заглушку
if (!$data) {
    $data = [
        'title' => 'Page Not Found',
        'content' => 'The requested page does not exist.'
    ];
}

// Рендерим страницу
echo $page->Render($data);