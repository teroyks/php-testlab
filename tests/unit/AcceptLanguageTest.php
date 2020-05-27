<?php

require 'src/AcceptLanguage.php';

use PHPUnit\Framework\TestCase;

class AcceptLanguageTest extends TestCase
{
    function testEmptyValue()
    {
        $this->assertNull(getCountryFromCode(''));
        $this->assertNull(parseSingleItem(''));
    }

    function testOnlyLanguageCode()
    {
        $this->assertNull(getCountryFromCode('fi'));
        $this->assertNull(parseSingleItem('fi'));
    }

    function testLanguageAndCountryCode()
    {
        $this->assertEquals('fi', getCountryFromCode('sv-FI'));
    }

    function testSingleItemWithDefaultPriority()
    {
        $this->assertEquals(['fi', 1], parseSingleItem('sv-FI'));
    }

    function testSingleItemWithPriority()
    {
        $this->assertEquals(['fi', .9], parseSingleItem('fi-FI;q=0.9'));
    }

    function testUnrecognizedCodeWithPriority()
    {
        $this->assertNull(parseSingleItem('fi;q=0.9'));
    }

    function testPreferredCode()
    {
        $this->assertEquals('ch', getCountryCode('fr-CH, fr;q=0.9, en;q=0.8, de;q=0.7, *;q=0.5'),
            'Should return country code from highest (default) value fr-CH.');
        $this->assertEquals('se', getCountryCode('ch, fi-FI;q=0.8, sv-SE;q=0.9, lt-LT;q=0.7'),
            'Should return country code with highest priority.');
    }
}