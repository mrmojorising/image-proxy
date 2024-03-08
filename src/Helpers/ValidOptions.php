<?php

namespace mrmojorising\ImageProxy\Helpers;

/**
 * @TODO: Class documentation
 */
class ValidOptions
{
    public const RESIZING_TYPES = [
        'fit',
        'fill',
        'fill-down',
        'force',
        'auto',
    ];

    public const RESIZING_ALGORITHMS = [
        'nearest',
        'linear',
        'cubic',
        'lanczos2',
        'lanczos3',
    ];

    public const ENLARGE = self::TRUE;
    public const EXTEND = self::TRUE;

    public const GRAVITY = [
        'no',
        'so',
        'ea',
        'we',
        'noea',
        'nowe',
        'soea',
        'sowe',
        'ce',
    ];

    public const TRIM_EQUAL = self::TRUE;
    public const AUTO_ROTATE = self::TRUE;

    public const ROTATE = [
        0,
        90,
        180,
        270,
    ];

    public const TRUE = [
        1,
        '1',
        't',
        'true',
    ];

    /**
     * @param string $resizingType
     * @return bool
     */
    public static function resizingType(string $resizingType): bool
    {
        return in_array($resizingType, self::RESIZING_TYPES);
    }

    /**
     * @param string $resizingAlgorithm
     * @return bool
     */
    public static function resizingAlgorithm(string $resizingAlgorithm): bool
    {
        return in_array($resizingAlgorithm, self::RESIZING_ALGORITHMS);
    }

    /**
     * @param float $zoom
     * @return bool
     */
    public static function zoom(float $zoom): bool
    {
        return $zoom > 0;
    }

    /**
     * @param float $dpr
     * @return bool
     */
    public static function dpr(float $dpr): bool
    {
        return $dpr > 0;
    }

    /**
     * @param string|int $enlarge
     * @return bool
     */
    public static function enlarge(string|int $enlarge): bool
    {
        return in_array($enlarge, self::ENLARGE);
    }

    /**
     * @param string|int $extend
     * @return bool
     */
    public static function extend(string|int $extend): bool
    {
        return in_array($extend, self::EXTEND);
    }

    /**
     * @param string $gravity
     * @return bool
     */
    public static function gravity(string $gravity): bool
    {
        return in_array($gravity, self::GRAVITY);
    }

    /**
     * @param float $gravityOffset
     * @return bool
     */
    public static function gravityOffset(float $gravityOffset): bool
    {
        return $gravityOffset > 0;
    }

    /**
     * @param float $crop
     * @return bool
     */
    public static function crop(float $crop): bool
    {
        return $crop > 0;
    }

    /**
     * @param string|int $trimEqual
     * @return bool
     */
    public static function trimEqual(string|int $trimEqual): bool
    {
        return in_array($trimEqual, self::TRIM_EQUAL);
    }

    /**
     * @param int $padding
     * @return bool
     */
    public static function padding(int $padding): bool
    {
        return $padding > 0;
    }

    /**
     * @param string|int $autoRotate
     * @return bool
     */
    public static function autoRotate(string|int $autoRotate): bool
    {
        return in_array($autoRotate, self::AUTO_ROTATE);
    }

    /**
     * @param int $rotate
     * @return bool
     */
    public static function rotate(int $rotate): bool
    {
        return in_array($rotate, self::ROTATE);
    }

    /**
     * @param int $rgb
     * @return bool
     */
    public static function backgroundRGB(int $rgb): bool
    {
        return $rgb >= 0 && $rgb <= 255;
    }

    /**
     * @param string $hexColour
     * @return bool
     */
    public static function backgroundHex(string $hexColour): bool
    {
        return preg_match('^#([a-f0-9]{6}|[a-f0-9]{3})\b$', $hexColour);
    }
}