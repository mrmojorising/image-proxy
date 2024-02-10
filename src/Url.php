<?php

namespace mrmojorising\ImageProxy;

use Base64Url\Base64Url;
use mrmojorising\ImageProxy\Helpers\ValidOptions;

/**
 *
 */
class Url extends ImageProxy
{
    public ?array $options = [];
    public ?array $imageUrls = [];

    /**
     * @param string $imageUrl
     * @return $this
     */
    public function setImageUrl(string $imageUrl): self
    {
        $this->imageUrls[] = $imageUrl;

        return $this;
    }

    /**
     * @param array $imageUrls
     * @return $this
     */
    public function setImageUrls(array $imageUrls): self
    {
        $this->imageUrls = $imageUrls;

        return $this;
    }

    /**
     * @param array $options
     * @return string|null
     */
    public static function optionsToPath(array $options): ?string
    {
        $optionsPath = null;
        foreach ($options as $key => $value) {
            $optionsPath .= $key . ':' . $value . '/';
        }

        return $optionsPath;
    }

    /**
     * @return string|array
     */
    public function generate(): string|array
    {
        $urlCount = count($this->imageUrls);
        if ($urlCount === 0) {
            return new \Exception('You must provide at least one Url to process');
        }

        $optionsPath = self::optionsToPath($this->options);

        if ($urlCount === 1) {
            $imageUrl = $this->imageUrls[0];
            $securePath = self::securePath($optionsPath, $imageUrl, $this->key);

            return sprintf('%s://%s/%s/%s%s', $this->protocol, $this->serverHost, $securePath, $optionsPath, base64_encode($imageUrl));
        }

        $imageProxyUrls = [];
        foreach ($this->imageUrls as $imageUrl) {
            $securePath = self::securePath($optionsPath, $imageUrl, $this->key);
            $imageProxyUrls[$imageUrl] = sprintf('%s://%s/%s/%s%s', $this->protocol, $this->serverHost, $securePath, $optionsPath, base64_encode($imageUrl));
        }

        return $imageProxyUrls;
    }

    public static function securePath($optionsPath, $imageUrl, $key): string
    {
        return $key ?
            Base64Url::encode(hash_hmac('sha256', $optionsPath . $imageUrl, $key, true)) :
            'insecure';
    }

    /**
     * Recommended is to use the helper functions provided, however if used it is
     * to be provided in format such as ->freetextOptions('w:200/h:300/rt:fill')
     * Onus is the user to ensure the option keys provided are valid as per ImgProxy documentation
     *
     * @param string $freetextOptions
     * @return $this
     */
    public function freetextOptions(string $freetextOptions): self
    {
        $options = explode(separator: '/', string: $freetextOptions);
        foreach ($options as $option) {
            if (is_string($option)) {
                $parsedOption = explode(separator: ':', string: $option);
                if (count($parsedOption) === 2) {
                    $this->options[$parsedOption[0]] = $parsedOption[1];
                }
            }
        }

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
     * User can provide a float but it will be converted to an integer
     *
     * @param int $width
     * @return $this
     */
    public function width(int $width): self
    {
        $this->options['w'] = $width;

        return $this;
    }

    /**
     * User can provide a float but it will be converted to an integer
     *
     * @param int $height
     * @return $this
     */
    public function height(int $height): self
    {
        $this->options['h'] = $height;

        return $this;
    }

    /**
     * User can provide a float but it will be converted to an integer
     *
     * @param int $minWidth
     * @return $this
     */
    public function minWidth(int $minWidth): self
    {
        $this->options['mw'] = $minWidth;

        return $this;
    }

    /**
     * User can provide a float but it will be converted to an integer
     * 
     * @param int $minHeight
     * @return $this
     */
    public function minHeight(int $minHeight): self
    {
        $this->options['mh'] = $minHeight;

        return $this;
    }

    /**
     * @param float $zoomXY
     * @param float|null $zoomY
     * @return $this
     */
    public function zoom(float $zoomXY, ?float $zoomY = null): self
    {
        if (ValidOptions::zoom($zoomXY)) {
            $this->options['z'] = $zoomXY;

            if (isset($zoomY) && ValidOptions::zoom($zoomY)) {
                $this->options['z'] = sprintf('%.2f:%.2f', $zoomXY, $zoomY);
            } elseif (isset($zoomY)) {
                unset($this->options['z']);
            }
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
        if (ValidOptions::enlarge($enlarge)) {
            $this->options['el'] = $enlarge;
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
    public function extend(string $extend, string $gravity = null, int $xOffset = 0, int $yOffset = 0): self
    {
        if (ValidOptions::extend($extend)) {
            $this->options['ex'] = $extend;
        }
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