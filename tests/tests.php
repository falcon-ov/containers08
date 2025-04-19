<?php
require_once __DIR__ . '/testframework.php';
require_once __DIR__ . '/../site/modules/database.php';
require_once __DIR__ . '/../site/modules/page.php';
require_once __DIR__ . '/../site/config.php';

// Инициализация тестового фреймворка
$tf = new TestFramework();

// Подготовка тестовой базы данных
$dbPath = $config["db"]["path"];
if (file_exists($dbPath)) {
    unlink($dbPath); // Удаляем старую базу для чистоты тестов
}
$db = new Database($dbPath);

// Создаем тестовую таблицу
$db->Execute("
    CREATE TABLE page (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        title TEXT,
        content TEXT
    )
");

// Тесты для класса Database
logMessage("Testing Database class...");

// Тест подключения к базе данных
$tf->assertTrue($db !== null, "Database connection established");

// Тест метода Count (пустая таблица)
$tf->assertEquals(0, $db->Count("page"), "Count returns 0 for empty table");

// Тест метода Create
$data = ['title' => 'Test Page', 'content' => 'Test Content'];
$newId = $db->Create("page", $data);
$tf->assertTrue($newId > 0, "Create returns valid ID");
$tf->assertEquals(1, $db->Count("page"), "Count returns 1 after creating a record");

// Тест метода Read
$record = $db->Read("page", $newId);
$tf->assertEquals('Test Page', $record['title'], "Read returns correct title");
$tf->assertEquals('Test Content', $record['content'], "Read returns correct content");

// Тест метода Update
$updatedData = ['title' => 'Updated Page', 'content' => 'Updated Content'];
$db->Update("page", $newId, $updatedData);
$updatedRecord = $db->Read("page", $newId);
$tf->assertEquals('Updated Page', $updatedRecord['title'], "Update changes title");
$tf->assertEquals('Updated Content', $updatedRecord['content'], "Update changes content");

// Тест метода Delete
$db->Delete("page", $newId);
$tf->assertEquals(0, $db->Count("page"), "Count returns 0 after deleting record");
$deletedRecord = $db->Read("page", $newId);
$tf->assertTrue($deletedRecord === false, "Read returns false for deleted record");

// Тесты для класса Page
logMessage("Testing Page class...");

// Тест создания объекта Page
$page = new Page(__DIR__ . '/../site/templates/index.tpl');
$tf->assertTrue($page !== null, "Page object created");

// Тест рендеринга страницы
$data = ['title' => 'Test Page', 'content' => 'Test Content'];
$output = $page->Render($data);
$tf->assertTrue(strpos($output, 'Test Page') !== false, "Rendered page contains title");
$tf->assertTrue(strpos($output, 'Test Content') !== false, "Rendered page contains content");
$tf->assertTrue(strpos($output, '<h1>') !== false, "Rendered page contains HTML structure");

// Вывод результатов
$tf->getSummary();

// Выход с кодом ошибки, если есть проваленные тесты
exit($tf->getSummary() ? 0 : 1);