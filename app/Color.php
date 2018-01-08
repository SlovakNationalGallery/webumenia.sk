<?php


namespace App;

use League\ColorExtractor\Color as ColorConverter;

class Color
{
    protected $value;

    protected $type;

    protected static $conversion_mapping = [
        self::TYPE_INT => [
            self::TYPE_HEX => [ColorConverter::class, 'fromIntToHex'],
            self::TYPE_RGB => [ColorConverter::class, 'fromIntToRgb'],
            self::TYPE_LAB => [ColorConverter::class, 'intColorToLab'],
        ],
        self::TYPE_HEX => [
            self::TYPE_INT => [ColorConverter::class, 'fromHexToInt'],
        ],
        self::TYPE_RGB => [
            self::TYPE_INT => [ColorConverter::class, 'fromRgbToInt'],
            self::TYPE_SRGB => [ColorConverter::class, 'rgbToSrgb'],
        ],
        self::TYPE_LAB => [
            self::TYPE_RGB => [ColorConverter::class, 'labToRgb'],
            self::TYPE_XYZ => [ColorConverter::class, 'labToXyz'],
        ],
        self::TYPE_XYZ => [
            self::TYPE_LAB => [ColorConverter::class, 'xyzToLab'],
            self::TYPE_SRGB => [ColorConverter::class, 'xyzToSrgb'],
        ],
        self::TYPE_SRGB => [
            self::TYPE_RGB => [ColorConverter::class, 'srgbToRgb'],
            self::TYPE_XYZ => [ColorConverter::class, 'srgbToXyz'],
        ],
    ];

    const TYPE_INT = 'int';
    const TYPE_HEX = 'hex';
    const TYPE_RGB = 'rgb';
    const TYPE_LAB = 'lab';
    const TYPE_XYZ = 'xyz';
    const TYPE_SRGB = 'srgb';

    /**
     * @param mixed $value
     * @param string $type
     */
    public function __construct($value, $type) {
        $this->setValue($value, $type);
    }

    /**
     * @param mixed $value
     * @param string|null $type
     */
    public function setValue($value, $type = null) {
        if ($type === null) {
            $type = $this->type;
        }

        self::validateValue($value, $type);

        $this->value = $value;
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getValue() {
        return $this->value;
    }

    /**
     * @return string
     */
    public function getType() {
        return $this->type;
    }

    /**
     * @return string
     */
    public function __toString() {
        return (string)$this->getValue();
    }

    /**
     * @param string $type
     * @return static
     */
    public function convertTo($type) {
        $value = $this->value;
        $visited = [];
        $path = [$this->type];

        while (true) {
            $conversions = static::$conversion_mapping[end($path)];

            if (isset($conversions[$type])) {
                $path[] = $type;
                break;
            }

            $keys = array_keys($conversions);
            $keys = array_diff($keys, $visited);

            if ($keys) {
                $visited[] = $path[] = reset($keys);
                continue;
            }

            if (!$path) {
                throw new \Exception('Cannot convert to specified type');
            }

            array_pop($path);
        }

        for ($i = 0; $i < count($path) - 1; $i++) {
            $conversion = static::$conversion_mapping[$path[$i]][$path[$i + 1]];
            $value = $conversion($value);
        }

        return new static($value, $type);
    }

    /**
     * @param mixed $value
     * @param string $type
     */
    public static function validateValue($value, $type) {
        $self = self::class;

        $validators = [
            self::TYPE_INT => [$self, 'validateInt'],
            self::TYPE_HEX => [$self, 'validateHex'],
            self::TYPE_RGB => [$self, 'validateRgb'],
            self::TYPE_LAB => [$self, 'validateLab'],
            self::TYPE_XYZ => [$self, 'validateXyz'],
            self::TYPE_SRGB => [$self, 'validateSrgb'],
        ];

        if (!isset($validators[$type])) {
            throw new \InvalidArgumentException('Unknown color type');
        }

        $validators[$type]($value);
    }

    protected static function validateInt($value) {
        if (!is_int($value)) {
            throw new \InvalidArgumentException('Value must be integer');
        }
    }

    protected static function validateHex($value) {
        if (!is_string($value)) {
            throw new \InvalidArgumentException('Value must be string');
        }
    }

    protected static function validateRgb($value) {
        self::validateArray($value, ['R', 'G', 'B']);
    }

    protected static function validateLab($value) {
        self::validateArray($value, ['L', 'a', 'b']);
    }

    protected static function validateXyz($value) {
        self::validateArray($value, ['X', 'Y', 'Z']);

    }

    protected static function validateSrgb($value) {
        self::validateArray($value, ['R', 'G', 'B']);
    }

    protected static function validateArray($value, $keys) {
        if (!is_array($value)) {
            throw new \InvalidArgumentException('Value must be array');
        }

        foreach ($keys as $key) {
            if (!array_key_exists($key, $value)) {
                throw new \InvalidArgumentException(sprintf('Array must have %s indices', implode(', ', $keys)));
            }
        }

        if (count($value) > count($keys)) {
            throw new \InvalidArgumentException(sprintf('Array must have only %s indices', implode(', ', $keys)));
        }
    }
}