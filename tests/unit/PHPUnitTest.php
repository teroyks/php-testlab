<?php

use PHPUnit\Framework\TestCase;

class EnvironmentTest extends TestCase
{
    function testPhpunitIsInstalled()
    {
        $this->assertTrue(true, 'Should run the test.');
    }
}