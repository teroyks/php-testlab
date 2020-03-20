<?php
/**
 * Test case for DateTime behaviour
 */
 class DateTimeTest extends PHPUnit\Framework\TestCase
 {
     function testMonthOverflow()
     {
         $date = new DateTime('2020-02-30'); // February has 29 days
         $this->assertEquals(new DateTime('2020-03-01'), $date, 'Should overflow to next month.');
     }

     /**
      * Tests moving the date to the same day of next month (is possible).
      * If next month has fewer days, goes to the last day of the month.
      */
     function testForwardToDayOfMonth()
     {
         $date = new DateTime('2020-01-30');
         $monthFromDate = new DateTime('2020-02-29'); // last day of the month
         $modifiedDate = (clone $date)->modify('+1 month');
         $this->assertNotEquals($monthFromDate, $modifiedDate);

         $lastDayOfNextMonth = (clone $date)->modify('last day of next month');
         $this->assertEquals($monthFromDate, min($lastDayOfNextMonth, $modifiedDate));
     }

     function testMonthDoesNotWrapAround()
     {
         $this->expectException(Exception::class);
         new DateTime('2020-13-01');
     }
 }