<?php
/**
 * Copyright (c) 2012-2015, Thomas Mueller <t_mueller_stolzenhain@yahoo.de>
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
 * @copyright 2012-2015 Thomas Mueller
 * @license   http://www.opensource.org/licenses/MIT MIT License
 * @link      https://github.com/mimmi20/BrowserDetector
 */

namespace BrowserDetector\Detector\Browser;

use BrowserDetector\Detector\Company;
use BrowserDetector\Detector\Engine\Blink;
use BrowserDetector\Detector\Engine\Webkit;
use BrowserDetector\Detector\MatcherInterface\Browser\BrowserHasWurflKeyInterface;
use BrowserDetector\Detector\Os\AbstractOs;
use BrowserDetector\Detector\Type\Browser as BrowserType;
use BrowserDetector\Detector\Version;

/**
 * @category  BrowserDetector
 * @package   BrowserDetector
 * @copyright 2012-2015 Thomas Mueller
 * @license   http://www.opensource.org/licenses/MIT MIT License
 */
class Amigo
    extends AbstractBrowser
    implements BrowserHasWurflKeyInterface
{
    /**
     * the detected browser properties
     *
     * @var array
     */
    protected $properties = array(
        // browser
        'mobile_browser_modus'         => null, // not in wurfl

        // product info
        'can_skip_aligned_link_row'    => true,
        'device_claims_web_support'    => true,
        // pdf
        'pdf_support'                  => true,
        // bugs
        'empty_option_value_support'   => true,
        'basic_authentication_support' => true,
        'post_method_support'          => true,
        // rss
        'rss_support'                  => false,
    );

    /**
     * Returns true if this handler can handle the given user agent
     *
     * @return bool
     */
    public function canHandle()
    {
        if (!$this->utils->checkIfContains('Mozilla/')) {
            return false;
        }

        if (!$this->utils->checkIfContainsAll(array('applewebkit', 'amigo'), true)) {
            return false;
        }

        $isNotReallyAnAmigo = array(
            // using also the KHTML rendering engine
            'Flock',
            'Galeon',
            'Lunascape',
            'Iron',
            'Maemo',
            'PaleMoon',
            'Rockmelt'
        );

        if ($this->utils->checkIfContains($isNotReallyAnAmigo)) {
            return false;
        }

        return true;
    }

    /**
     * gets the name of the browser
     *
     * @return string
     */
    public function getName()
    {
        return 'Amigo';
    }

    /**
     * gets the maker of the browser
     *
     * @return \BrowserDetector\Detector\Company\CompanyInterface
     */
    public function getManufacturer()
    {
        return new Company\Unknown();
    }

    /**
     * returns the type of the current device
     *
     * @return \BrowserDetector\Detector\Type\Device\TypeInterface
     */
    public function getBrowserType()
    {
        return new BrowserType\Browser();
    }

    /**
     * detects the browser version from the given user agent
     *
     * @return \BrowserDetector\Detector\Version
     */
    public function detectVersion()
    {
        $detector = new Version();
        $detector->setUserAgent($this->useragent);
        $detector->setMode(Version::COMPLETE | Version::IGNORE_MICRO);

        $searches = array('Amigo');

        return $detector->detectVersion($searches);
    }

    /**
     * gets the weight of the handler, which is used for sorting
     *
     * @return integer
     */
    public function getWeight()
    {
        return 302204;
    }

    /**
     * returns null, if the browser does not have a specific rendering engine
     * returns the Engine Handler otherwise
     *
     * @return \BrowserDetector\Detector\MatcherInterface\Engine\EngineInterface
     */
    public function getEngine()
    {
        $chrome = new Chrome();
        $chrome->setUserAgent($this->useragent);

        $chromeVersion = $chrome->detectVersion()->getVersion(Version::MAJORONLY);

        if ($chromeVersion >= 28) {
            $engine = new Blink();
        } else {
            $engine = new Webkit();
        }

        $engine->setUseragent($this->useragent);

        return $engine;
    }

    /**
     * returns the WurflKey
     *
     * @param \BrowserDetector\Detector\Os\AbstractOs $os
     *
     * @return string
     */
    public function getWurflKey(AbstractOs $os)
    {
        $version = $this->detectVersion()->getVersion(Version::MAJORONLY);

        return 'google_chrome_' . (int) $version;
    }
}
