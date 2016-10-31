<?php

namespace BrowserDetectorTest\Factory;

use BrowserDetector\Factory\EngineFactory;
use Cache\Adapter\Filesystem\FilesystemCachePool;
use League\Flysystem\Filesystem;
use League\Flysystem\Adapter\Local;

/**
 * Test class for \BrowserDetector\Detector\Device\Mobile\GeneralMobile
 */
class EngineFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \BrowserDetector\Factory\EngineFactory
     */
    private $object = null;

    /**
     * Sets up the fixture, for example, open a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $adapter      = new Local(__DIR__ . '/../../cache/');
        $cache        = new FilesystemCachePool(new Filesystem($adapter));
        $this->object = new EngineFactory($cache);
    }

    /**
     * @dataProvider providerDetect
     *
     * @param string $agent
     * @param string $engine
     * @param string $version
     * @param string $manufacturer
     * @param string $brand
     */
    public function testDetect($agent, $engine, $version, $manufacturer, $brand)
    {
        /** @var \UaResult\Engine\EngineInterface $result */
        $result = $this->object->detect($agent);

        self::assertInstanceOf('\UaResult\Engine\EngineInterface', $result);
        self::assertInternalType('string', $result->getName());
        self::assertSame(
            $engine,
            $result->getName(),
            'Expected engine name to be "' . $engine . '" (was "' . $result->getName() . '")'
        );

        self::assertInstanceOf('\BrowserDetector\Version\Version', $result->getVersion());
        self::assertSame(
            $version,
            $result->getVersion()->getVersion(),
            'Expected version to be "' . $version . '" (was "' . $result->getVersion()->getVersion() . '")'
        );

        self::assertInternalType('string', $result->getManufacturer());
        self::assertSame(
            $manufacturer,
            $result->getManufacturer(),
            'Expected manufacturer name to be "' . $manufacturer . '" (was "' . $result->getManufacturer() . '")'
        );

        self::assertInternalType('string', $result->getBrand());
        self::assertSame(
            $brand,
            $result->getBrand(),
            'Expected brand name to be "' . $brand . '" (was "' . $result->getBrand() . '")'
        );
    }

    /**
     * @return array[]
     */
    public function providerDetect()
    {
        return [
            [
                'Mozilla/5.0 (iPad; CPU OS 8_1_2 like Mac OS X) AppleWebKit/600.1.4 (KHTML, like Gecko) Version/8.0 Mobile/12B440 Safari/600.1.4',
                'WebKit',
                '600.1.4',
                'Apple Inc',
                'Apple',
            ],
            [
                'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.2; Trident/4.0; .NET CLR 1.1.4322; .NET CLR 2.0.50727; .NET CLR 3.0.4506.2152; .NET CLR 3.5.30729; .NET4.0C; .NET4.0E)',
                'Trident',
                '4.0.0',
                'Microsoft Corporation',
                'Microsoft',
            ],
            [
                'Mozilla/5.0 (Linux; U; Android 4.3; de-de; GT-I9300 Build/JSS15J) AppleWebKit/534.30 (KHTML, like Gecko) Version/4.0 Mobile Safari/534.30',
                'WebKit',
                '534.30.0',
                'Apple Inc',
                'Apple',
            ],
            [
                'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_1) AppleWebKit/600.1.25 (KHTML, like Gecko)',
                'WebKit',
                '600.1.25',
                'Apple Inc',
                'Apple',
            ],
            [
                'Mozilla/5.0 (Windows NT 6.3; WOW64; Trident/7.0; Touch; rv:11.0) like Gecko',
                'Trident',
                '7.0.0',
                'Microsoft Corporation',
                'Microsoft',
            ],
            [
                'Mozilla/5.0 (BB10; Touch) AppleWebKit/537.35+ (KHTML, like Gecko) Version/10.2.2.1609 Mobile Safari/537.35+',
                'WebKit',
                '537.35.0',
                'Apple Inc',
                'Apple',
            ],
            [
                'Mozilla/5.0 (Nintendo 3DS; U; ; de) Version/1.7567.EU',
                'NetFront',
                '0.0.0',
                'Access',
                'Access',
            ],
            [
                'Mozilla/5.0 (Mobile; Windows Phone 8.1; Android 4.0; ARM; Trident/7.0; Touch; rv:11.0; IEMobile/11.0; NOKIA; Lumia 930) like iPhone OS 7_0_3 Mac OS X AppleWebKit/537 (KHTML, like Gecko) Mobile Safari/537',
                'Trident',
                '7.0.0',
                'Microsoft Corporation',
                'Microsoft',
            ],
            [
                'Mozilla/5.0 (Windows NT 10.0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.71 Safari/537.36 Edge/12.0',
                'Edge',
                '12.0.0',
                'Microsoft Corporation',
                'Microsoft',
            ],
            [
                'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; ru) Opera 8.01',
                'Presto',
                '0.0.0',
                'Opera Software ASA',
                'Opera',
            ],
            [
                'Mozilla/4.0 (compatible; MSIE 6.0; PPC Mac OS X 10.6.8; Tasman 1.0)',
                'Tasman',
                '0.0.0',
                'Apple Inc',
                'Apple',
            ],
            [
                'Mozilla/5.0 (Windows NT 6.3; Win64; x64; rv:26.0.0b2) Goanna/20150828 Gecko/20100101 AppleWebKit/601.1.37 (KHTML, like Gecko) Version/9.0 Safari/601.1.37 PaleMoon/26.0.0b2',
                'Goanna',
                '1.0.0',
                'Moonchild Productions',
                'Moonchild',
            ],
        ];
    }
}
