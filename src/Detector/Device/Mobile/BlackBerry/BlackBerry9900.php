<?php
namespace BrowserDetector\Detector\Device\Mobile\BlackBerry;

/**
 * PHP version 5.3
 *
 * LICENSE:
 *
 * Copyright (c) 2013, Thomas Mueller <t_mueller_stolzenhain@yahoo.de>
 *
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *
 * * Redistributions of source code must retain the above copyright notice,
 *   this list of conditions and the following disclaimer.
 * * Redistributions in binary form must reproduce the above copyright notice,
 *   this list of conditions and the following disclaimer in the documentation
 *   and/or other materials provided with the distribution.
 * * Neither the name of the authors nor the names of its contributors may be
 *   used to endorse or promote products derived from this software without
 *   specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE
 * LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
 * CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
 * SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
 * INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
 * CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @category  BrowserDetector
 * @package   BrowserDetector
 * @copyright 2012-2013 Thomas Mueller
 * @license   http://opensource.org/licenses/BSD-3-Clause New BSD License
 */

use BrowserDetector\Detector\BrowserHandler;
use BrowserDetector\Detector\Company;
use BrowserDetector\Detector\DeviceHandler;
use BrowserDetector\Detector\EngineHandler;
use BrowserDetector\Detector\MatcherInterface;
use BrowserDetector\Detector\MatcherInterface\DeviceInterface;
use BrowserDetector\Detector\Os\RimOs;
use BrowserDetector\Detector\OsHandler;
use BrowserDetector\Detector\Type\Device as DeviceType;
use BrowserDetector\Detector\Version;

/**
 * @category  BrowserDetector
 * @package   BrowserDetector
 * @copyright 2012-2013 Thomas Mueller
 * @license   http://opensource.org/licenses/BSD-3-Clause New BSD License
 */
class BlackBerry9900
    extends DeviceHandler
    implements DeviceInterface
{
    /**
     * the detected browser properties
     *
     * @var array
     */
    protected $properties = array(
        'wurflKey'                => 'blackberry9900_ver1_subua71', // not in wurfl

        // device
        'model_name'              => 'BlackBerry Bold Touch 9900',
        'model_extra_info'        => null,
        'marketing_name'          => 'Dakota',
        'has_qwerty_keyboard'     => true,
        'pointing_method'         => 'touchscreen',

        // product info
        'can_assign_phone_number' => true, // wurflkey: blackberry9900_ver1_subua71
        'ununiqueness_handler'    => null,
        'uaprof'                  => 'http://www.blackberry.net/go/mobile/profiles/uaprof/9900_gprs/7.1.0.rdf',
        'uaprof2'                 => 'http://www.blackberry.net/go/mobile/profiles/uaprof/9900_edge/7.1.0.rdf',
        'uaprof3'                 => 'http://www.blackberry.net/go/mobile/profiles/uaprof/9900_umts/7.1.0.rdf',
        'unique'                  => true,

        // display
        'physical_screen_width'   => 57,
        'physical_screen_height'  => 43,
        'columns'                 => 28,
        'rows'                    => 16,
        'max_image_width'         => 640,
        'max_image_height'        => 480,
        'resolution_width'        => 640,
        'resolution_height'       => 480,
        'dual_orientation'        => false,
        'colors'                  => 256, // wurflkey: blackberry9900_ver1_subua71

        // sms
        'sms_enabled'             => true,

        // chips
        'nfc_support'             => true,
    );

    /**
     * checks if this device is able to handle the useragent
     *
     * @return boolean returns TRUE, if this device can handle the useragent
     */
    public function canHandle()
    {
        if (!$this->utils->checkIfContains('BlackBerry 9900')) {
            return false;
        }

        return true;
    }

    /**
     * gets the weight of the handler, which is used for sorting
     *
     * @return integer
     */
    public function getWeight()
    {
        return 3;
    }

    /**
     * returns the type of the current device
     *
     * @return \BrowserDetector\Detector\Type\Device\TypeInterface
     */
    public function getDeviceType()
    {
        return new DeviceType\MobilePhone();
    }

    /**
     * returns the type of the current device
     *
     * @return \BrowserDetector\Detector\Company\CompanyInterface
     */
    public function getManufacturer()
    {
        return new Company\Rim();
    }

    /**
     * returns the type of the current device
     *
     * @return \BrowserDetector\Detector\Company\CompanyInterface
     */
    public function getBrand()
    {
        return new Company\Rim();
    }

    /**
     * returns null, if the device does not have a specific Operating System
     * returns the OS Handler otherwise
     *
     * @return \BrowserDetector\Detector\Os\RimOs
     */
    public function detectOs()
    {
        $handler = new RimOs();
        $handler->setUseragent($this->_useragent);

        return $handler;
    }

    /**
     * detects properties who are depending on the browser, the rendering engine
     * or the operating system
     *
     * @param \BrowserDetector\Detector\BrowserHandler $browser
     * @param \BrowserDetector\Detector\EngineHandler  $engine
     * @param \BrowserDetector\Detector\OsHandler      $os
     *
     * @return DeviceHandler
     */
    public function detectDependProperties(
        BrowserHandler $browser, EngineHandler $engine, OsHandler $os
    ) {
        $osVersion = $os->getVersion()->getVersion(
            Version::MAJORMINOR
        );

        if ('7.0' == $osVersion) {
            $this->setCapability(
                'uaprof',
                'http://www.blackberry.net/go/mobile/profiles/uaprof/9900/7.0.0.rdf'
            );
            $this->setCapability('wurflKey', 'blackberry9900_ver1_sub_os7');
        }

        parent::detectDependProperties($browser, $engine, $os);

        return $this;
    }
}