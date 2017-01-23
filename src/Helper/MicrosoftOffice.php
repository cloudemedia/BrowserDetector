<?php
/**
 * Copyright (c) 2012-2017, Thomas Mueller <mimmi20@live.de>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a
 * copy of this software and associated documentation files (the "Software"),
 * to deal in the Software without restriction, including without limitation
 * the rights to use, copy, modify, merge, publish, distribute, sublicense,
 * and/or sell copies of the Software, and to permit persons to whom the
 * Software is furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included
 * in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS
 * OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @category  BrowserDetector
 *
 * @author    Thomas Mueller <mimmi20@live.de>
 * @copyright 2012-2017 Thomas Mueller
 * @license   http://www.opensource.org/licenses/MIT MIT License
 *
 * @link      https://github.com/mimmi20/BrowserDetector
 */

namespace BrowserDetector\Helper;

/**
 * @category  BrowserDetector
 *
 * @copyright 2012-2017 Thomas Mueller
 * @license   http://www.opensource.org/licenses/MIT MIT License
 */
class MicrosoftOffice
{
    /**
     * maps the version
     *
     * @param string $version
     *                        returns null, if the device does not have a specific Operating System, returns the OS Handler otherwise
     *
     * @return string
     */
    public function mapVersion($version)
    {
        if (15 === (int) $version) {
            return '2013';
        }

        if (14 === (int) $version) {
            return '2010';
        }

        if (12 === (int) $version) {
            return '2007';
        }

        return '0.0';
    }

    /**
     * detects the browser version from the given user agent
     *
     * @param string $useragent
     *
     * @return string
     */
    public function detectInternalVersion($useragent)
    {
        $doMatch = preg_match(
            '/MSOffice ([\d\.]+)/',
            $useragent,
            $matches
        );

        if ($doMatch) {
            return $matches[1];
        }

        $doMatch = preg_match('/MSOffice (\d+)/', $useragent, $matches);

        if ($doMatch) {
            return $matches[1];
        }

        $doMatch = preg_match(
            '/microsoft Office\/([\d\.]+)/',
            $useragent,
            $matches
        );

        if ($doMatch) {
            return $matches[1];
        }

        $doMatch = preg_match(
            '/microsoft Office\/(\d+)/',
            $useragent,
            $matches
        );

        if ($doMatch) {
            return $matches[1];
        }

        return '0.0';
    }
}
