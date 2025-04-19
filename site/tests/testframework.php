<?php
class TestFramework {
    private $testCount = 0;
    private $passed = 0;
    private $failed = 0;

    public function assertEquals($expected, $actual, $message) {
        $this->testCount++;
        if ($expected === $actual) {
            $this->passed++;
            echo "PASS: $message\n";
        } else {
            $this->failed++;
            echo "FAIL: $message (Expected: " . var_export($expected, true) . ", Got: " . var_export($actual, true) . ")\n";
        }
    }

    public function assertTrue($condition, $message) {
        $this->assertEquals(true, $condition, $message);
    }

    public function assertFalse($condition, $message) {
        $this->assertEquals(false, $condition, $message);
    }

    public function getSummary() {
        return "Tests run: $this->testCount, Passed: $this->passed, Failed: $this->failed";
    }
}

function logMessage($message) {
    echo "$message\n";
}