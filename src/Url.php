<?php

namespace mrmojorising\ImageProxy;

use mrmojorising\ImageProxy\Helpers\ValidOptions;

/**
 *
 */
class Url extends ImageProxy
{
    public ?string $freetext = null;
    public ?array $options = null;

    public static function ping()
    {
        return 'ping';
    }

    /**
     * @param string $imageUrl
     * @return string
     */
    public function generate(string $imageUrl): string
    {
        return $imageUrl;
    }

    /**
     * @param array $imgUrls
     * @return array
     */
    public function generateBatch(array $imgUrls): array
    {
        $imgProxyUrls = [];

        foreach ($imgUrls as $imgUrl) {
            $imgProxyUrls[] = self::generate($imgUrl);
        }

        return $imgProxyUrls;
    }

    /**
     * @param string $freetext
     * @return $this
     */
    public function freetext(string $freetext): self
    {
        $this->freetext = $freetext;

        return $this;
    }

    /**
     * @param array $options
     * @return $this
     */
    public function resize(array $options): self
    {
        $availableOptions = ['resizingType', 'width', 'height', 'extend', 'enlarge'];
        foreach ($availableOptions as $availableOption) {
            if (isset($options[$availableOption])) {
                $this->{$availableOption}($options[$availableOption]);
            }
        }

        return $this;
    }

    public function size(array $options): self
    {
        $availableOptions = ['width', 'height', 'extend', 'enlarge'];
        foreach ($availableOptions as $availableOption) {
            if (isset($options[$availableOption])) {
                $this->{$availableOption}($options[$availableOption]);
            }
        }

        return $this;
    }

    /**
     * @param string $resizingType
     * @return $this
     */
    public function resizingType(string $resizingType): self
    {
        if (ValidOptions::resizingType($resizingType)) {
            $this->options['rt'] = $resizingType;
        }

        return $this;
    }

    /**
     * @param int $width
     * @return $this
     */
    public function width(int $width): self
    {
        $this->options['w'] = $width;

        return $this;
    }

    /**
     * @param int $height
     * @return $this
     */
    public function height(int $height): self
    {
        $this->options['h'] = $height;

        return $this;
    }

    /**
     * @param int $minWidth
     * @return $this
     */
    public function minWidth(int $minWidth): self
    {
        $this->options['mw'] = $minWidth;

        return $this;
    }

    /**
     * @param int $minHeight
     * @return $this
     */
    public function minHeight(int $minHeight): self
    {
        $this->options['mh'] = $minHeight;

        return $this;
    }

    /**
     * @param int $zoomXY
     * @param int $zoomX
     * @param int $zoomY
     * @return $this
     */
    public function zoom(int $zoomXY = 0, int $zoomX = 0, int $zoomY = 0): self
    {
        if (isset($zoomXY)) {
            $this->options['z'] = $zoomXY;
        } elseif (isset($zoomX) && isset($zoomY)) {
            $this->options['z'] = sprintf('%d:%d', $zoomX, $zoomY);
        }

        return $this;
    }

    /**
     * @param int $dpr
     * @return $this
     */
    public function dpr(int $dpr): self
    {
        $this->options['dpr'] = $dpr;

        return $this;
    }

    /**
     * @param string $enlarge
     * @return $this
     */
    public function enlarge(string $enlarge): self
    {
        $this->options['el'] = $enlarge;

        return $this;
    }

    /**
     * @param string $extend
     * @param string|null $gravity
     * @param int $xOffset
     * @param int $yOffset
     * @return $this
     */
    public function extend(string $extend, string $gravity = null, int $xOffset = 0, int $yOffset = 0): self
    {
        $this->options['ex'] = $extend;
        if (isset($gravity)) {
            $this->gravity($gravity, $xOffset, $yOffset);
        }

        return $this;
    }

    /**
     * @param string $extend
     * @param string|null $gravity
     * @param int $xOffset
     * @param int $yOffset
     * @return $this
     */
    public function extendAspectRatio(string $extend, string $gravity = null, int $xOffset = 0, int $yOffset = 0): self
    {
        $this->options['exar'] = $extend;
        if (isset($gravity)) {
            $this->gravity($gravity, $xOffset, $yOffset);
        }

        return $this;
    }

    /**
     * @param string $gravity
     * @param int $xOffset
     * @param int $yOffset
     * @return $this
     */
    public function gravity(string $gravity, int $xOffset = 0, int $yOffset = 0): self
    {
        if (ValidOptions::gravity($gravity)) {
            $this->options['g'] = sprintf('%s:%d:%d', $gravity, $xOffset, $yOffset);
        }

        return $this;
    }
}