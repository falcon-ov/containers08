<?php
class TestFramework {
    private $testCount = 0;
    private $passed = 0;
    private $failed = 0;

    public function assertTrue($condition, $message) {
        $this->testCount++;
        if ($condition) {
            $this->passed++;
            echo "✓ Test $this->testCount passed: $message\n";
        } else {
            $this->failed++;
            echo "✗ Test $this->testCount failed: $message\n";
        }
    }

    public function assertEquals($expected, $actual, $message) {
        $this->assertTrue($expected === $actual, "$message (Expected: $expected, Got: $actual)");
    }

    public function getSummary() {
        echo "\nTest Summary:\n";
        echo "Total Tests: $this->testCount\n";
        echo "Passed: $this->passed\n";
        echo "Failed: $this->failed\n";
        return $this->failed === 0;
    }
}

// Функция для логирования
function logMessage($message) {
    echo "$message\n";
}