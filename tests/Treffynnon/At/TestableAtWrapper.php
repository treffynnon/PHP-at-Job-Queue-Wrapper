<?php
namespace Treffynnon\At\Tests;

use Treffynnon\At\Wrapper as At;

class TestableAtWrapper extends At
{
    /**
     * @return string
     */
    public static function getQueueRegex()
    {
        return static::$queueRegex;
    }
}
