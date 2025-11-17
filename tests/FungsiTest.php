<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../library/fungsi.php';

class FungsiTest extends TestCase {
    public function testRupiahExecutionTime() {
        $oop = new oop(); // buat objek dari class

        $start = microtime(true);
        ob_start();
        $oop->rupiah(1000000); // panggil method di class
        ob_get_clean();
        $end = microtime(true);

        $executionTime = $end - $start;
        fwrite(STDOUT, "Waktu eksekusi rupiah(): {$executionTime} detik\n");

        $this->assertLessThan(1, $executionTime);
    }
}
