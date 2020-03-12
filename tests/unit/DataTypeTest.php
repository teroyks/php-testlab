<?php

class DataTypeTest extends PHPUnit\Framework\TestCase
{
    function testNullIsNotNumeric()
    {
        $this->assertFalse(is_numeric(null), 'Should not be numeric.');
    }
}