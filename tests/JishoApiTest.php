<?php


namespace App\Tests;


use App\JishoApi\JishoApi;
use PHPUnit\Framework\TestCase;

/**
 * Class JishoApiTest
 * @group unit
 */
class JishoApiTest extends TestCase
{
    function testJishoExitOk(): void
    {
        $jisho = new JishoApi('漢字');
        $this->assertSame(true, $jisho->getJishoExist());
    }

    function testJishoExistNok(): void
    {
        $jisho = new JishoApi('');
        $this->assertSame(false, $jisho->getJishoExist());
    }
}