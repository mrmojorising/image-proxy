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

    public const TRUE = [
        'l',
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
     * @param string $enlarge
     * @return bool
     */
    public static function enlarge(string $enlarge): bool
    {
        return in_array($enlarge, self::ENLARGE);
    }

    /**
     * @param string $extend
     * @return bool
     */
    public static function extend(string $extend): bool
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
}