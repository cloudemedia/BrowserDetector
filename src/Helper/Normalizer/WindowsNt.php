<?php
/**
 * This file is part of the browser-detector package.
 *
 * Copyright (c) 2012-2017, Thomas Mueller <mimmi20@live.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types = 1);
namespace BrowserDetector\Helper\Normalizer;

/**
 * User Agent Normalizer - removes UCWEB garbage from user agent
 */
class WindowsNt implements NormalizerInterface
{
    /**
     * This method remove the 'UP.Link' substring from user agent string.
     *
     * @param string $userAgent
     *
     * @return string Normalized user agent
     */
    public function normalize(string $userAgent): string
    {
        return preg_replace('/windows[ \-]nt/i', 'Windows NT', $userAgent);
    }
}
