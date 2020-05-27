<?php

require 'src/AcceptLanguage.php';

use PHPUnit\Framework\TestCase;

class AcceptLanguageTest extends TestCase
{
    function testEmptyValue()
    {
        $this->assertNull(parseCountryFromCode(''));
        $this->assertNull(parseSingleItem(''));
    }

    function testOnlyLanguageCode()
    {
        $this->assertNull(parseCountryFromCode('fi'));
        $this->assertNull(parseSingleItem('fi'));
        $this->assertNull(parseCountryFromCode('*'));
    }

    function testLanguageAndCountryCode()
    {
        $this->assertEquals('fi', parseCountryFromCode('sv-FI'));
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

    function testSingleCountryCode()
    {
        $this->assertEquals('fi', getCountryCode('fi-FI'));
    }

    function testPreferredCode()
    {
        $this->assertEquals('ch', getCountryCode('fr-CH, fr;q=0.9, en;q=0.8, de;q=0.7, *;q=0.5'),
            'Should return country code from highest (default) value fr-CH.');
        $this->assertEquals('se', getCountryCode('ch, fi-FI;q=0.8, sv-SE;q=0.9, lt-LT;q=0.7'),
            'Should return country code with highest priority.');
    }

    function testAcceptedCodes()
    {
        $this->assertEquals('fi',
            getCountryCode('fr-ch;q=0.9, fi-FI;q=0.8, sv-SE;q=0.7', ['fi', 'se']),
            'Should exclude CH even though it has the highest priority.'
        );
        $this->assertEquals('se',
            getCountryCode('fr-ch;q=1.0, fi-FI;q=0.8, sv-SE;q=0.85', ['fi', 'se']),
            'Should pick allowed code with highest priority.'
        );
    }
}