<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class ExampleTest extends TestCase
{
    public function testMathWorks(): void
    {
        $this->assertEquals(2, 1 + 1);
    }
}
