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

namespace BrowserDetector\Detector\Factory;

use BrowserDetector\Detector\Browser\Chrome;
use BrowserDetector\Detector\Engine;
use BrowserDetector\Version\Version;
use BrowserDetector\Version\VersionFactory;
use Stringy\Stringy;
use UaResult\Os\OsInterface;

/**
 * Browser detection class
 *
 * @category  BrowserDetector
 *
 * @author    Thomas Mueller <t_mueller_stolzenhain@yahoo.de>
 * @copyright 2012-2016 Thomas Mueller
 * @license   http://www.opensource.org/licenses/MIT MIT License
 */
class EngineFactory implements FactoryInterface
{
    /**
     * Gets the information about the rendering engine by User Agent
     *
     * @param string                   $useragent
     * @param \UaResult\Os\OsInterface $os
     *
     * @return \UaResult\Engine\EngineInterface
     */
    public static function detect($useragent, OsInterface $os = null)
    {
        $s = new Stringy($useragent);
        $engineKey = 'unknown';

        if ($s->contains('Edge')) {
            $engineKey = 'edge';
        } elseif ($s->contains(' U2/')) {
            $engineKey = 'u2';
        } elseif ($s->contains(' U3/')) {
            $engineKey = 'u3';
        } elseif ($s->contains(' T5/')) {
            $engineKey = 't5';
        } elseif (preg_match('/(msie|trident|outlook|kkman)/i', $useragent)
            && false === stripos($useragent, 'opera')
            && false === stripos($useragent, 'tasman')
        ) {
            $engineKey = 'trident';
        } elseif (preg_match('/(goanna)/i', $useragent)) {
            $engineKey = 'goanna';
        } elseif (preg_match('/(applewebkit|webkit|cfnetwork|safari|dalvik)/i', $useragent)) {
            $chrome  = new Chrome($useragent);
            $version = $chrome->getVersion();

            if (null !== $version) {
                $chromeVersion = $version->getVersion(Version::MAJORONLY);
            } else {
                $chromeVersion = 0;
            }

            if ($chromeVersion >= 28) {
                $engineKey = 'blink';
            } else {
                $engineKey = 'webkit';
            }
        } elseif (preg_match('/(KHTML|Konqueror)/', $useragent)) {
            $engineKey = 'khtml';
        } elseif (preg_match('/(tasman)/i', $useragent)
            || $s->containsAll(['MSIE', 'Mac_PowerPC'])
        ) {
            $engineKey = 'tasman';
        } elseif (preg_match('/(Presto|Opera)/', $useragent)) {
            $engineKey = 'presto';
        } elseif (preg_match('/(Gecko|Firefox)/', $useragent)) {
            $engineKey = 'gecko';
        } elseif (preg_match('/(NetFront\/|NF\/|NetFrontLifeBrowserInterface|NF3|Nintendo 3DS)/', $useragent)
            && !$s->containsAny(['Kindle'])
        ) {
            $engineKey = 'netfront';
        } elseif ($s->contains('BlackBerry')) {
            $engineKey = 'blackberry';
        } elseif (preg_match('/(Teleca|Obigo)/', $useragent)) {
            $engineKey = 'teleca';
        }

        return self::get($engineKey, $useragent);
    }

    /**
     * @param string $engineKey
     * @param string $useragent
     *
     * @return \UaResult\Engine\Engine
     */
    public static function get($engineKey, $useragent)
    {
        static $engines = null;

        if (null === $engines) {
            $engines = json_decode(file_get_contents(__DIR__ . '/data/engines.json'));
        }

        if (!isset($engines->$engineKey)) {
            return new \UaResult\Engine\Engine('unknown', 'unknown', 'unknown', new Version(0));
        }

        $engineVersionClass = $engines->$engineKey->version->class;

        if (!is_string($engineVersionClass)) {
            $version = new Version(0);
        } elseif ('VersionFactory' === $engineVersionClass) {
            $version = VersionFactory::detectVersion($useragent, $engines->$engineKey->version->search);
        } else {
            $version = $engineVersionClass::detectVersion($useragent);
        }

        return new \UaResult\Engine\Engine(
            $engines->$engineKey->name,
            $engines->$engineKey->manufacturer,
            $engines->$engineKey->brand,
            $version
        );
    }
}
