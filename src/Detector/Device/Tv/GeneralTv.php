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

namespace BrowserDetector\Detector\Device\Tv;

use BrowserDetector\Detector\Chain;
use BrowserDetector\Detector\Company;
use BrowserDetector\Detector\Device\AbstractDevice;
use BrowserDetector\Helper\Tv as TvHelper;
use UaDeviceType\Tv;
use UaMatcher\Device\DeviceHasChildrenInterface;

/**
 * @category  BrowserDetector
 *
 * @copyright 2012-2015 Thomas Mueller
 * @license   http://www.opensource.org/licenses/MIT MIT License
 */
class GeneralTv extends AbstractDevice implements DeviceHasChildrenInterface
{
    /**
     * the detected browser properties
     *
     * @var array
     */
    protected $properties = [
        // device
        'code_name'              => 'general TV Device',
        'model_extra_info'       => null,
        'marketing_name'         => 'general TV Device',
        'has_qwerty_keyboard'    => null,
        'pointing_method'        => null,
        // product info
        'ununiqueness_handler'   => null,
        'uaprof'                 => null,
        'uaprof2'                => null,
        'uaprof3'                => null,
        'unique'                 => true,
        // display
        'physical_screen_width'  => null,
        'physical_screen_height' => null,
        'columns'                => null,
        'rows'                   => null,
        'max_image_width'        => null,
        'max_image_height'       => null,
        'resolution_width'       => null,
        'resolution_height'      => null,
        'dual_orientation'       => false,
        'colors'                 => 65536,
        // sms
        'sms_enabled'            => false,
        // chips
        'nfc_support'            => false,
    ];

    /**
     * checks if this device is able to handle the useragent
     *
     * @return bool returns TRUE, if this device can handle the useragent
     */
    public function canHandle()
    {
        return (!(new TvHelper($this->useragent))->isTvDevice());
    }

    /**
     * detects the device name from the given user agent
     *
     * @return \UaMatcher\Device\DeviceInterface
     */
    public function detectDevice()
    {
        $chain = new Chain();
        $chain->setUserAgent($this->useragent);
        $chain->setNamespace('\BrowserDetector\Detector\Device\Tv');
        $chain->setDirectory(
            __DIR__ . DIRECTORY_SEPARATOR . 'Tv' . DIRECTORY_SEPARATOR
        );
        $chain->setDefaultHandler($this);

        $device = $chain->detect();

        if ($device !== $this && $device instanceof DeviceHasChildrenInterface) {
            $device = $device->detectDevice();
        }

        return $device;
    }

    /**
     * gets the weight of the handler, which is used for sorting
     *
     * @return int
     */
    public function getWeight()
    {
        return 2;
    }

    /**
     * returns the type of the current device
     *
     * @return \UaDeviceType\TypeInterface
     */
    public function getDeviceType()
    {
        return new Tv();
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
}