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
 *
 * @author    Thomas Mueller <t_mueller_stolzenhain@yahoo.de>
 * @copyright 2012-2015 Thomas Mueller
 * @license   http://www.opensource.org/licenses/MIT MIT License
 *
 * @link      https://github.com/mimmi20/BrowserDetector
 */

namespace BrowserDetector\Detector\Device\Mobile;

use BrowserDetector\Detector\Company;
use BrowserDetector\Helper\MobileDevice;
use UaDeviceType\MobilePhone;
use UaDeviceType\Tablet;
use UaMatcher\Browser\BrowserInterface;
use UaMatcher\Device\DeviceHasRuntimeModificationsInterface;
use UaMatcher\Device\DeviceHasWurflKeyInterface;
use UaMatcher\Engine\EngineInterface;
use UaMatcher\Os\OsInterface;
use UaResult\Version;

/**
 * @category  BrowserDetector
 *
 * @copyright 2012-2015 Thomas Mueller
 * @license   http://www.opensource.org/licenses/MIT MIT License
 */
class GeneralMobile
    extends AbstractDevice
    implements DeviceHasWurflKeyInterface, DeviceHasRuntimeModificationsInterface
{
    /**
     * the detected browser properties
     *
     * @var array
     */
    protected $properties = [
        // device
        'code_name'              => 'general Mobile Device',
        'model_extra_info'       => null,
        'marketing_name'         => 'general Mobile Device',
        'has_qwerty_keyboard'    => true,
        'pointing_method'        => 'touchscreen',
        // product info
        'ununiqueness_handler'   => null,
        'uaprof'                 => null,
        'uaprof2'                => null,
        'uaprof3'                => null,
        'unique'                 => true,
        // display
        'physical_screen_width'  => 40,
        'physical_screen_height' => 60,
        'columns'                => 15,
        'rows'                   => 12,
        'max_image_width'        => 240,
        'max_image_height'       => 320,
        'resolution_width'       => 240,
        'resolution_height'      => 320,
        'dual_orientation'       => true,
        'colors'                 => 65536,
        // sms
        'sms_enabled'            => null,
        // chips
        'nfc_support'            => null,
    ];
    /**
     * @var MobilePhone
     */
    private $deviceType = null;

    /**
     * checks if this device is able to handle the useragent
     *
     * @return bool returns TRUE, if this device can handle the useragent
     */
    public function canHandle()
    {
        return (new MobileDevice($this->useragent))->isMobile();
    }

    /**
     * gets the weight of the handler, which is used for sorting
     *
     * @return int
     */
    public function getWeight()
    {
        return 3;
    }

    /**
     * returns the type of the current device
     *
     * @return \UaDeviceType\TypeInterface
     */
    public function getDeviceType()
    {
        if (null === $this->deviceType) {
            $this->deviceType = new \UaDeviceType\MobileDevice();
        }

        return $this->deviceType;
    }

    /**
     * returns the type of the current device
     *
     * @return \UaMatcher\Company\CompanyInterface
     */
    public function getManufacturer()
    {
        return new Company(new Company\Unknown());
    }

    /**
     * returns the type of the current device
     *
     * @return \UaMatcher\Company\CompanyInterface
     */
    public function getBrand()
    {
        return new Company(new Company\Unknown());
    }

    /**
     * detects properties who are depending on the device version or the user
     * agent
     *
     * @return \BrowserDetector\Detector\Device\GeneralMobile
     */
    public function detectSpecialProperties()
    {
        if (preg_match('/Puffin\/[\d\.]+AT/', $this->useragent)) {
            $this->deviceType = new Tablet();

            $this->setCapability('code_name', 'general Tablet');
            $this->setCapability('marketing_name', 'general Tablet');

            return $this;
        }

        if (preg_match('/Puffin\/[\d\.]+AP/', $this->useragent)) {
            $this->deviceType = new MobilePhone();

            $this->setCapability('code_name', 'general Mobile Phone');
            $this->setCapability('marketing_name', 'general Mobile Phone');

            return $this;
        }

        if ($this->utils->checkIfContains(['Android; Tablet'])) {
            $this->deviceType = new Tablet();

            $this->setCapability('code_name', 'general Tablet');
            $this->setCapability('marketing_name', 'general Tablet');
            $this->setCapability('physical_screen_width', 112);
            $this->setCapability('physical_screen_height', 187);
            $this->setCapability('columns', 40);
            $this->setCapability('rows', 40);
            $this->setCapability('max_image_width', 320);
            $this->setCapability('max_image_height', 400);
            $this->setCapability('resolution_width', 800);
            $this->setCapability('resolution_height', 480);
            $this->setCapability('dual_orientation', true);
            $this->setCapability('sms_enabled', true);
            $this->setCapability('nfc_support', true);

            return $this;
        }

        if ($this->utils->checkIfContains(['Android; Mobile', 'Android; Linux', 'Browser/AppleWebKit'])) {
            $this->deviceType = new MobilePhone();

            $this->setCapability('code_name', 'general Mobile Phone');
            $this->setCapability('marketing_name', 'general Mobile Phone');

            return $this;
        }

        if ($this->utils->checkIfContains(['Opera Tablet'])) {
            $this->deviceType = new Tablet();

            $this->setCapability('code_name', 'general Tablet');
            $this->setCapability('marketing_name', 'general Tablet');
            $this->setCapability('physical_screen_width', 100);
            $this->setCapability('physical_screen_height', 200);
            $this->setCapability('columns', 60);
            $this->setCapability('rows', 40);
            $this->setCapability('max_image_width', 480);
            $this->setCapability('max_image_height', 640);
            $this->setCapability('resolution_width', 640); // 1280 bei Ver 11, Android 3.2
            $this->setCapability('resolution_height', 480); // 768 bei Ver 11, Android 3.2
            $this->setCapability('dual_orientation', true);

            return $this;
        }

        if ($this->utils->checkIfContains(['XBLWP7', 'ZuneWP7', 'WPDesktop'])) {
            $this->deviceType = new MobilePhone();

            $this->setCapability('code_name', 'general Mobile Phone');
            $this->setCapability('marketing_name', 'general Mobile Phone');
            $this->setCapability('physical_screen_width', 50);
            $this->setCapability('physical_screen_height', 84);
            $this->setCapability('columns', 12);
            $this->setCapability('rows', 20);
            $this->setCapability('max_image_width', 320);
            $this->setCapability('max_image_height', 480);
            $this->setCapability('resolution_width', 480);
            $this->setCapability('resolution_height', 800);
            $this->setCapability('dual_orientation', true);
            $this->setCapability('has_qwerty_keyboard', true);
            $this->setCapability('pointing_method', 'touchscreen');
            $this->setCapability('sms_enabled', true);
            $this->setCapability('nfc_support', true);

            return $this;
        }

        if ($this->utils->checkIfContains(['Opera Mini'])) {
            $this->deviceType = new MobilePhone();

            $this->setCapability('code_name', 'general Mobile Phone');
            $this->setCapability('marketing_name', 'general Mobile Phone');
            $this->setCapability('physical_screen_width', 34);
            $this->setCapability('physical_screen_height', 50);
            $this->setCapability('columns', 60);
            $this->setCapability('rows', 40);
            $this->setCapability('max_image_width', 320);
            $this->setCapability('max_image_height', 400);
            $this->setCapability('resolution_width', 320);
            $this->setCapability('resolution_height', 480);
            $this->setCapability('dual_orientation', true);
            $this->setCapability('has_qwerty_keyboard', true);
            $this->setCapability('pointing_method', 'touchscreen');

            return $this;
        }

        if ($this->utils->checkIfContains(['Opera', 'OPR'])) {
            $this->deviceType = new MobilePhone();

            $this->setCapability('code_name', 'general Mobile Phone');
            $this->setCapability('marketing_name', 'general Mobile Phone');
            $this->setCapability('physical_screen_width', 34);
            $this->setCapability('physical_screen_height', 50);
            $this->setCapability('columns', 60);
            $this->setCapability('rows', 40);
            $this->setCapability('max_image_width', 320);
            $this->setCapability('max_image_height', 400);
            $this->setCapability('resolution_width', 320);
            $this->setCapability('resolution_height', 480);
            $this->setCapability('dual_orientation', true);
            $this->setCapability('has_qwerty_keyboard', true);
            $this->setCapability('pointing_method', 'touchscreen');

            return $this;
        }

        if ($this->utils->checkIfContains(['Windows Phone 6.5'])) {
            $this->deviceType = new MobilePhone();

            $this->setCapability('code_name', 'general Mobile Phone');
            $this->setCapability('marketing_name', 'general Mobile Phone');
            $this->setCapability('physical_screen_width', 34);
            $this->setCapability('physical_screen_height', 50);
            $this->setCapability('columns', 60);
            $this->setCapability('rows', 40);
            $this->setCapability('max_image_width', 320);
            $this->setCapability('max_image_height', 400);
            $this->setCapability('resolution_width', 320);
            $this->setCapability('resolution_height', 480);
            $this->setCapability('dual_orientation', false);
            $this->setCapability('has_qwerty_keyboard', false);
            $this->setCapability('pointing_method', 'stylus');
            $this->setCapability('colors', 4096);

            return $this;
        }

        if ($this->utils->checkIfContainsAll(['Windows NT', 'Touch'])) {
            $this->deviceType = new Tablet();

            $this->setCapability('code_name', 'general Tablet');
            $this->setCapability('marketing_name', 'general Tablet');
            $this->setCapability('physical_screen_width', 100);
            $this->setCapability('physical_screen_height', 200);
            $this->setCapability('columns', 60);
            $this->setCapability('rows', 40);
            $this->setCapability('max_image_width', 480);
            $this->setCapability('max_image_height', 640);
            $this->setCapability('resolution_width', 640);
            $this->setCapability('resolution_height', 480);
            $this->setCapability('dual_orientation', true);

            return $this;
        }

        if ($this->utils->checkIfContains(['Mobile'])) {
            $this->deviceType = new MobilePhone();

            $this->setCapability('code_name', 'general Mobile Phone');
            $this->setCapability('marketing_name', 'general Mobile Phone');
            $this->setCapability('physical_screen_width', 34);
            $this->setCapability('physical_screen_height', 50);
            $this->setCapability('columns', 60);
            $this->setCapability('rows', 40);
            $this->setCapability('max_image_width', 320);
            $this->setCapability('max_image_height', 400);
            $this->setCapability('resolution_width', 320);
            $this->setCapability('resolution_height', 480);
            $this->setCapability('dual_orientation', false);
            $this->setCapability('has_qwerty_keyboard', false);
            $this->setCapability('pointing_method', 'stylus');
            $this->setCapability('colors', 4096);

            return $this;
        }

        $mobiles = [
            'AdobeAIR',
            'YUANDA',
            'stagefright',
            'AndroidDownloadManager',
            'WAP Browser',
            'Crowsnest',
            'Dorado',
        ];

        if ($this->utils->checkIfContains($mobiles)) {
            $this->deviceType = new MobilePhone();

            $this->setCapability('code_name', 'general Mobile Phone');
            $this->setCapability('marketing_name', 'general Mobile Phone');
            $this->setCapability('physical_screen_width', 34);
            $this->setCapability('physical_screen_height', 50);
            $this->setCapability('columns', 60);
            $this->setCapability('rows', 40);
            $this->setCapability('max_image_width', 320);
            $this->setCapability('max_image_height', 400);
            $this->setCapability('resolution_width', 320);
            $this->setCapability('resolution_height', 480);
            $this->setCapability('dual_orientation', false);
            $this->setCapability('has_qwerty_keyboard', false);
            $this->setCapability('pointing_method', 'stylus');
            $this->setCapability('colors', 4096);

            return $this;
        }

        return $this;
    }

    /**
     * returns the WurflKey for the device
     *
     * @param \UaMatcher\Browser\BrowserInterface $browser
     * @param \UaMatcher\Engine\EngineInterface   $engine
     * @param \UaMatcher\Os\OsInterface           $os
     *
     * @return string|null
     */
    public function getWurflKey(BrowserInterface $browser, EngineInterface $engine, OsInterface $os)
    {
        $wurflKey = 'generic_mobile';

        if ($this->utils->checkIfContains(['XBLWP7', 'ZuneWP7'])) {
            $wurflKey = 'generic_ms_phone_os7_5_desktopmode';
        } elseif ($this->utils->checkIfContains(['Opera Mobi'])) {
            $wurflKey = 'generic_android_ver4_0_opera_mobi';
        } elseif ($this->utils->checkIfContains(['Opera Mini'])) {
            $wurflKey = 'generic_opera_mini_android';
        } elseif ($this->utils->checkIfContains(['Windows Phone 6.5'])) {
            $wurflKey = 'generic_opera_mini_android';
        }

        $brwoserName = $browser->getName();

        switch ($brwoserName) {
            case 'Firefox':
                if ('Android' === $os->getName()) {
                    $os->detectVersion()->setVersion('2.0');

                    if ($this->getDeviceType()->isTablet()) {
                        $wurflKey = 'generic_android_ver2_0_fennec_tablet';
                    } else {
                        $wurflKey = 'generic_android_ver2_0_fennec';
                    }
                }
                break;
            case 'Opera Mobile':
                if ('Android' === $os->getName()) {
                    $osVersion = $os->detectVersion()->getVersion(Version::MAJORMINOR);

                    switch ((float) $osVersion) {
                        case 4.0:
                            $wurflKey = 'generic_android_ver4_0_opera_mobi';
                            break;
                        case 4.1:
                            $wurflKey = 'uabait_opera_mobi_android_4_1_ver12';
                            break;
                        default:
                            // nothing to do here
                            break;
                    }
                } elseif ('Windows Mobile OS' === $os->getName()) {
                    $this->setCapability('has_qwerty_keyboard', false);
                    $this->setCapability('pointing_method', 'stylus');
                    $this->setCapability('resolution_width', 240);
                    $this->setCapability('resolution_height', 320);
                    $this->setCapability('dual_orientation', false);
                    $this->setCapability('colors', 4096);
                } elseif ('Symbian OS' === $os->getName()) {
                    $this->setCapability('has_qwerty_keyboard', false);
                    $this->setCapability('pointing_method', null);
                    $this->setCapability('resolution_width', 240);
                    $this->setCapability('resolution_height', 320);
                    $this->setCapability('dual_orientation', false);
                    $this->setCapability('colors', 4096);
                }
                break;
            case 'Opera Tablet':
                if ('Android' === $os->getName()) {
                    $osVersion = $os->detectVersion()->getVersion(Version::MAJORMINOR);

                    if (3.2 === (float) $osVersion) {
                        $wurflKey = 'generic_android_ver3_2_opera_tablet';

                        $this->setCapability('resolution_width', 1280);
                        $this->setCapability('resolution_height', 768);
                    }
                }
                break;
            case 'Opera Mini':
                if ('Android' === $os->getName()) {
                    $osVersion = $os->detectVersion()->getVersion(Version::MAJORMINOR);

                    if (5.0 === (float) $osVersion) {
                        $wurflKey = 'generic_opera_mini_android_version5';
                    }

                    $this->setCapability('resolution_width', 240);
                    $this->setCapability('resolution_height', 320);
                    $this->setCapability('dual_orientation', false);
                } elseif ('Java' === $os->getName()) {
                    $wurflKey = 'uabait_opera_mini_v10_op98';

                    $this->setCapability('colors', 256);
                }
                break;
            default:
                // nothing to do
                break;
        }

        if ($this->utils->checkIfContains(['XBLWP7', 'ZuneWP7', 'WPDesktop'])) {
            $browser->setCapability('mobile_browser_modus', 'Desktop Mode');
        }

        if ($this->getDeviceType()->isTablet()) {
            $this->setCapability('sms_enabled', false);
            $this->setCapability('nfc_support', false);
        } else {
            $this->setCapability('sms_enabled', true);
            $this->setCapability('nfc_support', true);
        }

        return $wurflKey;
    }
}