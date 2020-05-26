<?php

require 'src/foo.php';

use PHPUnit\Framework\TestCase;

class IncludeTest extends TestCase
{
    function testRequireWorks()
    {
        $this->assertTrue(foo(), 'Should run the included function.');
    }
}