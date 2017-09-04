<?php

namespace App\Descriptors;

use League\ColorExtractor\Color;
use League\ColorExtractor\ColorExtractor;

class ColorDescriptor
{
    /** @var ColorExtractor */
    protected $extractor;

    /** @var int */
    protected $colorCount;

    const TRESHOLD_AMOUNT = 0.01;

    /**
     * @param int $colorCount
     */
    public function __construct(ColorExtractor $extractor, $colorCount)
    {
        $this->extractor = $extractor;
        $this->colorCount = $colorCount;
    }

    /**
     * @return int
     */
    public function getDimensions() {
        return $this->colorCount * 4;
    }

    /**
     * @param array $colors
     * @return array
     */
    public function describe($filename) {
        $colors = $this->extractor->extract($filename, $this->colorCount);

        $blocks = [];
        $excluded = [];
        foreach ($colors as $color => $amount) {
            $lab = Color::intColorToLab($color);
            $block = [$lab['L'], $lab['a'], $lab['b'], sqrt($amount * 100)];

            if ($amount < self::TRESHOLD_AMOUNT) {
                $excluded[] = $block;
                continue;
            }

            $blocks[] = $block;
        }

        usort($blocks, self::createCompareFunction(0));

        if (empty($blocks)) {
            $blocks = $excluded;
        }

        $blocks = $this->multiplyBlocks($blocks);

        return array_flatten($blocks);
    }

    protected function multiplyBlocks($blocks) {
        $multiplier = array_fill(0, count($blocks), 1);

        $emptyBlocks = $this->colorCount - count($blocks);
        while ($emptyBlocks--) {
            $max = null;
            $index = null;
            foreach ($blocks as $i => $block) {
                $amount = $block[3];
                if ($max < $amount / ($multiplier[$i] + 1)) {
                    $max = $amount / ($multiplier[$i] + 1);
                    $index = $i;
                }
            }

            $multiplier[$index]++;
        }

        $offset = 0;
        foreach ($blocks as $i => $block) {
            while (--$multiplier[$i]) {
                array_splice($blocks, $i + $offset++, 0, [$block]);
            }
        }

        return $blocks;
    }

    protected static function createCompareFunction($index) {
        return function ($a, $b) use ($index) {
            $a = $a[$index];
            $b = $b[$index];

            return $a == $b ? 0 : ($a < $b ? 1 : -1);
        };
    }
}