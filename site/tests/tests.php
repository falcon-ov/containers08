<?php
require_once __DIR__ . '/testframework.php';
require_once __DIR__ . '/../modules/database.php';
require_once __DIR__ . '/../modules/page.php';
require_once __DIR__ . '/../config.php';

class Tests {
    private $tf;
    private $db;
    private $page;

    public function __construct() {
        $this->tf = new TestFramework();
        $this->db = new Database($config["db"]["path"]);
        $this->page = new Page(__DIR__ . '/../templates/index.tpl');
    }

    public function run() {
        $this->testDatabaseConnection();
        $this->testDatabaseCount();
        $this->testDatabaseCreate();
        $this->testDatabaseRead();
        $this->testDatabaseUpdate();
        $this->testDatabaseDelete();
        $this->testPageRender();
        echo $this->tf->getSummary();
    }

    private function testDatabaseConnection() {
        $this->tf->assertTrue($this->db !== null, "Database connection should be established");
    }

    private function testDatabaseCount() {
        $count = $this->db->Count("page");
        $this->tf->assertTrue($count >= 3, "Database should have at least 3 pages");
    }

    private function testDatabaseCreate() {
        $data = ['title' => 'Test Page', 'content' => 'Test Content'];
        $id = $this->db->Create("page", $data);
        $this->tf->assertTrue($id > 0, "Create should return a valid ID");
    }

    private function testDatabaseRead() {
        $data = $this->db->Read("page", 1);
        $this->tf->assertTrue(isset($data['title']) && $data['title'] === 'Page 1', "Read should return correct page data");
    }

    private function testDatabaseUpdate() {
        $data = ['title' => 'Updated Page', 'content' => 'Updated Content'];
        $this->db->Update("page", 1, $data);
        $updated = $this->db->Read("page", 1);
        $this->tf->assertEquals('Updated Page', $updated['title'], "Update should change page title");
    }

    private function testDatabaseDelete() {
        $data = ['title' => 'Delete Test', 'content' => 'Delete Content'];
        $id = $this->db->Create("page", $data);
        $this->db->Delete("page", $id);
        $deleted = $this->db->Read("page", $id);
        $this->tf->assertFalse($deleted, "Delete should remove the page");
    }

    private function testPageRender() {
        $data = ['title' => 'Test Page', 'content' => 'Test Content'];
        $output = $this->page->Render($data);
        $this->tf->assertTrue(strpos($output, 'Test Page') !== false, "Page render should include title");
        $this->tf->assertTrue(strpos($output, 'Test Content') !== false, "Page render should include content");
    }
}

$tests = new Tests();
$tests->run();