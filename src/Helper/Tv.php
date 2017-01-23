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

use Stringy\Stringy;

/**
 * a helper to detect TV devices
 */
class Tv
{
    /**
     * @var string the user agent to handle
     */
    private $useragent = '';

    /**
     * Class Constructor
     *
     * @param string $useragent
     *
     * @return \BrowserDetector\Helper\Tv
     */
    public function __construct($useragent)
    {
        $this->useragent = $useragent;
    }

    /**
     * @return bool
     */
    public function isTvDevice()
    {
        $s = new Stringy($this->useragent);

        if ($s->containsAll(['windows nt', 'iphone', 'micromessenger'], false)) {
            return false;
        }

        $noDesktops = [
            'new-sogou-spider',
            'zollard',
            'socialradarbot',
            'microsoft office protocol discovery',
            'powermarks',
            'archivebot',
            'dino762',
            'marketwirebot',
            'microsoft-cryptoapi',
            'pad-bot',
            'terra_101',
            'butterfly',
            'james bot',
            'winhttp',
            'jobboerse',
            '<',
            '>',
        ];

        if ($s->containsAny($noDesktops, false)) {
            return false;
        }

        $tvDevices = [
            'boxee',
            'ce-html',
            'dlink.dsm380',
            'googletv',
            'hbbtv',
            'idl-6651n',
            'kdl40ex720',
            'netrangemmh',
            'loewe; sl121',
            'loewe; sl150',
            'smart-tv',
            'sonydtv',
            'viera',
            'xbox',
            'espial',
            'aquosbrowser',
            'gxt_dongle_3188',
            'lf1v307',
            'lf1v325',
            'lf1v373',
            'lf1v394',
            'lf1v401',
            'apple tv',
            'mxl661l32',
            'nettv',
        ];

        if (!$s->containsAny($tvDevices, false)) {
            return false;
        }

        return true;
    }
}
