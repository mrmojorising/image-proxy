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

    /**
     * @param array $options
     * @return $this
     */
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
     * @param string $resizingAlgorithm
     * @return $thiss
     */
    public function resizingAlgorithm(string $resizingAlgorithm): self
    {
        if (ValidOptions::resizingAlgorithm($resizingAlgorithm)) {
            $this->options['ra'] = $resizingAlgorithm;
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
     * @param float $dpr
     * @return $this
     */
    public function dpr(float $dpr): self
    {
        if (ValidOptions::dpr($dpr)) {
            $this->options['dpr'] = $dpr;
        }

        return $this;
    }

    /**
     * @param string|int $enlarge
     * @return $this
     */
    public function enlarge(string|int $enlarge): self
    {
        if (ValidOptions::enlarge($enlarge)) {
            $this->options['el'] = $enlarge;
        }

        return $this;
    }

    /**
     * @param string|int $extend
     * @param string|null $gravity
     * @param int $xOffset
     * @param int $yOffset
     * @return $this
     */
    public function extend(string|int $extend, string $gravity = null, int $xOffset = 0, int $yOffset = 0): self
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
     * @param float $xOffset
     * @param float $yOffset
     * @return $this
     */
    public function gravity(string $gravity, float $xOffset = 0, float $yOffset = 0): self
    {
        if (ValidOptions::gravity($gravity)) {
            if (!ValidOptions::gravityOffset($xOffset)) {
                $xOffset = 0;
            }
            if (!ValidOptions::gravityOffset($yOffset)) {
                $yOffset = 0;
            }
            $this->options['g'] = sprintf('%s:%.2f:%.2f', $gravity, $xOffset, $yOffset);
        }

        return $this;
    }

    /**
     * @param float $width
     * @param float $height
     * @param string|null $gravity
     * @return $this
     */
    public function crop(float $width, float $height, string $gravity = null): self
    {
        if (!ValidOptions::crop($width)) {
            $width = 0;
        }
        if (!ValidOptions::crop($height)) {
            $height = 0;
        }
        if (ValidOptions::gravity($gravity)) {
            $this->options['c'] = sprintf('%.2f:%.2f:%s', $width, $height, $gravity);
        } else {
            $this->options['c'] = sprintf('%.2f:%.2f', $width, $height);
        }

        return $this;
    }

    /**
     * @param $threshold
     * @param string|null $colour
     * @param string|int|null $equalHorizontal
     * @param string|int|null $equalVertical
     * @return $this
     */
    public function trim(
        $threshold,
        string $colour = null,
        string|int $equalHorizontal = null,
        string|int $equalVertical = null): self
    {
        if (!ValidOptions::trimEqual($equalHorizontal)) {
            $equalHorizontal = false;
        }
        if (!ValidOptions::trimEqual($equalVertical)) {
            $equalVertical = false;
        }

        $this->options['t'] = sprintf('%s:%s:%s:%s', $threshold, $colour, $equalHorizontal, $equalVertical);

        return $this;
    }

    /**
     * @param int $top
     * @param int $right
     * @param int $bottom
     * @param int $left
     * @return $this
     */
    public function padding(int $top, int $right = 0, int $bottom = 0, int $left = 0)
    {
        if (!ValidOptions::padding($left)) {
            $left = $right;
        }
        if (!ValidOptions::padding($bottom)) {
            $bottom = $top;
        }
        if (!ValidOptions::padding($right)) {
            $right = $top;
            $left = $top;
        }
        $this->options['pd'] = sprintf('%s:%s:%s:%s', $top, $right, $bottom, $left);

        return $this;
    }

    /**
     * @param string|int $autoRotate
     * @return $this
     */
    public function autoRotate(string|int $autoRotate): self
    {
        if (ValidOptions::autoRotate($autoRotate)) {
            $this->options['ar'] = $autoRotate;
        }

        return $this;
    }

    /**
     * @param int $rotate
     * @return $this
     */
    public function rotate(int $rotate): self
    {
        if (ValidOptions::rotate($rotate)) {
            $this->options['rot'] = $rotate;
        }

        return $this;
    }

    /**
     * @param int $red
     * @param int $green
     * @param int $blue
     * @return $this
     */
    public function backgroundRGB(int $red = 0, int $green = 0, int $blue = 0): self
    {
        if (!ValidOptions::backgroundRGB($red)) {
            $red = 0;
        }
        if (!ValidOptions::backgroundRGB($green)) {
            $green = 0;
        }
        if (!ValidOptions::backgroundRGB($blue)) {
            $blue = 0;
        }

        $this->options['bg'] = sprintf('%d:%d:%d', $red, $green, $blue);

        return $this;
    }

    /**
     * @param string $backgroundHexColour
     * @return $this
     */
    public function backgroundHex(string $backgroundHexColour): self
    {
        if (ValidOptions::backgroundHex($backgroundHexColour)) {
            $this->options['bg'] = $backgroundHexColour;
        }

        return $this;
    }

    /**
     * @param float $backgroundAlpha
     * @return self
     */
    public function backgroundAlpha(float $backgroundAlpha = 0): self
    {
        if (ValidOptions::backgroundAlpha($backgroundAlpha)) {
            $this->options['bga'] = $backgroundAlpha;
        }

        return $this;
    }

    /**
     * @param array $options
     * @return $this
     */
    public function adjust(array $options = []): self
    {
        $availableOptions = ['brightness', 'contrast', 'saturation'];
        foreach ($availableOptions as $availableOption) {
            if (isset($options[$availableOption])) {
                $this->{$availableOption}($options[$availableOption]);
            }
        }

        return $this;
    }

    /**
     * @param int $brightness
     * @return $this
     */
    public function brightness(int $brightness): self
    {
        if (ValidOptions::brightness($brightness)) {
            $this->options['br'] = $brightness;
        }

        return $this;
    }

    /**
     * @param float $contrast
     * @return $this
     */
    public function contrast(float $contrast): self
    {
        if (ValidOptions::contrast($contrast)) {
            $this->options['co'] = $contrast;
        }

        return $this;
    }

    /**
     * @param float $saturation
     * @return $this
     */
    public function saturation(float $saturation): self
    {
        if (ValidOptions::saturation($saturation)) {
            $this->options['sa'] = $saturation;
        }

        return $this;
    }

    /**
     * @param float $blur
     * @return $this
     */
    public function blur(float $blur): self
    {
        if (ValidOptions::blur($blur)) {
            $this->options['bl'] = $blur;
        }

        return $this;
    }

    /**
     * @param float $sharpen
     * @return $this
     */
    public function sharpen(float $sharpen): self
    {
        if (ValidOptions::sharpen($sharpen)) {
            $this->options['sh'] = $sharpen;
        }

        return $this;
    }

    /**
     * @param float $pixelate
     * @return $this
     */
    public function pixelate(float $pixelate): self
    {
        if (ValidOptions::pixelate($pixelate)) {
            $this->options['pix'] = $pixelate;
        }

        return $this;
    }
}