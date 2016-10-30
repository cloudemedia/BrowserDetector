<?php
/**
 * Copyright (c) 2012-2016, Thomas Mueller <t_mueller_stolzenhain@yahoo.de>
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
 * @author    Thomas Mueller <t_mueller_stolzenhain@yahoo.de>
 * @copyright 2012-2016 Thomas Mueller
 * @license   http://www.opensource.org/licenses/MIT MIT License
 *
 * @link      https://github.com/mimmi20/BrowserDetector
 */

namespace BrowserDetector\Factory\Device\Mobile;

use BrowserDetector\Factory;

/**
 * @category  BrowserDetector
 *
 * @copyright 2012-2016 Thomas Mueller
 * @license   http://www.opensource.org/licenses/MIT MIT License
 */
class HpFactory implements Factory\FactoryInterface
{
    /**
     * detects the device name from the given user agent
     *
     * @param string $useragent
     *
     * @return \UaResult\Device\DeviceInterface
     */
    public function detect($useragent)
    {
        $deviceCode = 'general hp device';

        if (preg_match('/ipaqhw6900/i', $useragent)) {
            $deviceCode = 'ipaq 6900';
        }

        if (preg_match('/slate 17/i', $useragent)) {
            $deviceCode = 'slate 17';
        }

        if (preg_match('/slate 10 hd/i', $useragent)) {
            $deviceCode = 'slate 10';
        }

        if (preg_match('/(touchpad|cm\_tenderloin)/i', $useragent)) {
            $deviceCode = 'touchpad';
        }

        if (preg_match('/palm\-d050/i', $useragent)) {
            $deviceCode = 'tx';
        }

        if (preg_match('/pre\//i', $useragent)) {
            $deviceCode = 'pre';
        }

        if (preg_match('/pixi\//i', $useragent)) {
            $deviceCode = 'pixi';
        }

        if (preg_match('/blazer/i', $useragent)) {
            $deviceCode = 'blazer';
        }

        if (preg_match('/p160u/i', $useragent)) {
            $deviceCode = 'p160u';
        }

        return (new Factory\DeviceFactory())->get($deviceCode, $useragent);
    }
}
