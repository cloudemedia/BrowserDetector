<?php
/**
 * Copyright (c) 2012-2014, Thomas Mueller <t_mueller_stolzenhain@yahoo.de>
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
 * @package   BrowserDetector
 * @author    Thomas Mueller <t_mueller_stolzenhain@yahoo.de>
 * @copyright 2012-2014 Thomas Mueller
 * @license   http://www.opensource.org/licenses/MIT MIT License
 * @link      https://github.com/mimmi20/BrowserDetector
 */

namespace BrowserDetector\Detector\Bits;

use BrowserDetector\Helper\Utils;

/**
 * Class to detect the Bit count for an Device
 *
 * @category  BrowserDetector
 * @package   BrowserDetector
 * @copyright 2012-2014 Thomas Mueller
 * @license   http://www.opensource.org/licenses/MIT MIT License
 */
class Device
{
    /**
     * @var string the user agent to handle
     */
    private $useragent = null;

    /**
     * @var string the bits of the detected browser
     */
    private $_bits = null;

    /**
     * @var Utils
     */
    private $utils = null;

    /**
     * class constructor
     */
    public function __construct()
    {
        $this->utils = new Utils();
    }

    /**
     * sets the user agent to be handled
     *
     * @return Device
     */
    public function setUserAgent($userAgent)
    {
        $this->useragent = $userAgent;
        $this->utils->setUserAgent($this->useragent);

        return $this;
    }

    /**
     * @return string
     * @throws \UnexpectedValueException
     */
    public function getBits()
    {
        if (null === $this->useragent) {
            throw new \UnexpectedValueException(
                'You have to set the useragent before calling this function'
            );
        }

        $this->_detectBits();

        return $this->_bits;
    }

    /**
     * detects the bit count by this browser from the given user agent
     *
     * @return Device
     */
    private function _detectBits()
    {
        // 64 bits
        if ($this->utils->checkIfContains(
            array('x64', 'Win64', 'x86_64', 'amd64', 'AMD64', 'ppc64', 'i686 on x86_64')
        )
        ) {
            $this->_bits = '64';

            return $this;
        }

        // old deprecated 16 bit windows systems
        if ($this->utils->checkIfContains(array('Win3.1', 'Windows 3.1'))) {
            $this->_bits = '16';

            return $this;
        }

        // general windows or a 32 bit browser on a 64 bit system (WOW64)
        if ($this->utils->checkIfContains(
            array('Win', 'WOW64', 'i586', 'i686', 'i386', 'i486', 'i86', 'Intel Mac OS X', 'Android', 'PPC', 'x86')
        )
        ) {
            $this->_bits = '32';

            return $this;
        }

        // old deprecated 8 bit systems
        if ($this->utils->checkIfContains(array('CP/M', '8-bit'))) {
            $this->bits = '8';

            return $this;
        }

        $this->_bits = '32';

        return $this;
    }
}