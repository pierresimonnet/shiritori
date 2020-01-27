<?php


namespace App\Tests\Utils;


use App\Utils\WordSplit;
use PHPUnit\Framework\TestCase;

/**
 * Class WordSplitTest
 * @group unit
 */
class WordSplitTest extends TestCase
{
    /**
     * @dataProvider getSplit
     * @param string $value
     * @param array $split
     */
    public function testSplit(string $value, array $split): void
    {
        $this->assertSame($split, WordSplit::split($value));
    }

    public function getSplit(): iterable
    {
        yield ['字', [0 => '字']];
        yield ['漢字', ['first' => '漢', 'last' => '字']];
        yield ['日本', ['first' => '日', 'last' => '本']];
        yield ['日本史', ['first' => '日', 'last' => '史']];
        yield ['日本史', ['first' => '日', 'last' => '史']];
        yield ['金沢大学人間社会学域学校教育学類附属特別支援学校', ['first' => '金', 'last' => '校']];
    }
}