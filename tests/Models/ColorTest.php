<?php


namespace Tests;


use App\Color;

class ColorTest extends TestCase
{
    public function testIncorrectInt() {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Value must be integer');
        new Color('incorrect', Color::TYPE_INT);
    }

    public function testNewInstanceAfterConversion() {
        $int = new Color(0, Color::TYPE_INT);
        $hex = $int->convertTo(Color::TYPE_HEX);

        $this->assertNotSame($int, $hex);
    }

    public function testConvertFromIntToHex() {
        $int = new Color(0, Color::TYPE_INT);
        $hex = $int->convertTo(Color::TYPE_HEX);

        $this->assertEquals($hex->getValue(), '#000000');
    }

    public function testConvertFromIntToRgb() {
        $int = new Color(0, Color::TYPE_INT);
        $rgb = $int->convertTo(Color::TYPE_RGB);

        $this->assertEquals($rgb->getValue(), ['R' => 0, 'G' => 0, 'B' => 0]);
    }

    public function testConvertFromIntToLab() {
        $int = new Color(0, Color::TYPE_INT);
        $lab = $int->convertTo(Color::TYPE_LAB);

        $this->assertEquals($lab->getValue(), ['L' => 0, 'a' => 0, 'b' => 0]);
    }

    public function testConvertFromIntToXyz() {
        $int = new Color(0, Color::TYPE_INT);
        $xyz = $int->convertTo(Color::TYPE_XYZ);

        $this->assertEquals($xyz->getValue(), ['X' => 0, 'Y' => 0, 'Z' => 0]);
    }

    public function testConvertFromIntToSrgb() {
        $int = new Color(0, Color::TYPE_INT);
        $srgb = $int->convertTo(Color::TYPE_SRGB);

        $this->assertEquals($srgb->getValue(), ['R' => 0, 'G' => 0, 'B' => 0]);
    }
}