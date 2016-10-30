<?php

namespace BrowserDetectorTest\Factory\Device\Mobile;

use BrowserDetector\Factory\Device\Mobile\WikoFactory;

/**
 * Test class for \BrowserDetector\Detector\Device\Mobile\GeneralMobile
 */
class WikoFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider providerDetect
     *
     * @param string $agent
     * @param string $deviceName
     * @param string $marketingName
     * @param string $manufacturer
     * @param string $brand
     * @param string $deviceType
     * @param bool   $dualOrientation
     * @param string $pointingMethod
     */
    public function testDetect($agent, $deviceName, $marketingName, $manufacturer, $brand, $deviceType, $dualOrientation, $pointingMethod)
    {
        /** @var \UaResult\Device\DeviceInterface $result */
        $result = (new WikoFactory())->detect($agent);

        self::assertInstanceOf('\UaResult\Device\DeviceInterface', $result);

        self::assertSame(
            $deviceName,
            $result->getDeviceName(),
            'Expected device name to be "' . $deviceName . '" (was "' . $result->getDeviceName() . '")'
        );
        self::assertSame(
            $marketingName,
            $result->getMarketingName(),
            'Expected marketing name to be "' . $marketingName . '" (was "' . $result->getMarketingName() . '")'
        );
        self::assertSame(
            $manufacturer,
            $result->getManufacturer(),
            'Expected manufacturer name to be "' . $manufacturer . '" (was "' . $result->getManufacturer() . '")'
        );
        self::assertSame(
            $brand,
            $result->getBrand(),
            'Expected brand name to be "' . $brand . '" (was "' . $result->getBrand() . '")'
        );
        self::assertSame(
            $deviceType,
            $result->getType()->getName(),
            'Expected device type to be "' . $deviceType . '" (was "' . $result->getType()->getName() . '")'
        );
        self::assertSame(
            $dualOrientation,
            $result->getDualOrientation(),
            'Expected dual orientation to be "' . $dualOrientation . '" (was "' . $result->getDualOrientation() . '")'
        );
        self::assertSame(
            $pointingMethod,
            $result->getPointingMethod(),
            'Expected pointing method to be "' . $pointingMethod . '" (was "' . $result->getPointingMethod() . '")'
        );
    }

    /**
     * @return array[]
     */
    public function providerDetect()
    {
        return [
            [
                'Mozilla/5.0 (Linux; Android 6.0; JERRY Build/MRA58K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/52.0.2743.98 Mobile Safari/537.36',
                'JERRY',
                'JERRY',
                'Wiko',
                'Wiko',
                'Mobile Phone',
                true,
                'touchscreen',
            ],
            [
                'Mozilla/5.0 (Linux; Android 4.4.2; BLOOM Build/KOT49H) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/52.0.2743.98 Mobile Safari/537.36',
                'BLOOM',
                'BLOOM',
                'Wiko',
                'Wiko',
                'Mobile Phone',
                true,
                'touchscreen',
            ],
            [
                'Dalvik/1.6.0 (Linux; U; Android 4.2.1; DARKSIDE Build/JOP40D)',
                'DARKSIDE',
                'DARKSIDE',
                'Wiko',
                'Wiko',
                'Mobile Phone',
                true,
                'touchscreen',
            ],
            [
                'Mozilla/5.0 (Linux; Android 5.1; SLIDE2 Build/LMY47I) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/43.0.2357.93 Mobile Safari/537.36',
                'Slide 2',
                'Slide 2',
                'Wiko',
                'Wiko',
                'Mobile Phone',
                true,
                'touchscreen',
            ],
        ];
    }
}
