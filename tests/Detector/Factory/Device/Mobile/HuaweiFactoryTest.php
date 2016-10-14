<?php

namespace BrowserDetectorTest\Detector\Factory\Device\Mobile;

use BrowserDetector\Detector\Factory\Device\Mobile\HuaweiFactory;

/**
 * Test class for \BrowserDetector\Detector\Device\Mobile\GeneralMobile
 */
class HuaweiFactoryTest extends \PHPUnit_Framework_TestCase
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
        $result = HuaweiFactory::detect($agent);

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
                'UCWEB/2.0 (Linux; U; Opera Mini/7.1.32052/30.3697; en-US; HUAWEI G510-0100 Build/HuaweiG510-0100) U2/1.0.0 UCBrowser/10.1.5.583 Mobile',
                'G510-0100',
                'Ascend G510',
                'Huawei',
                'Huawei',
                'Mobile Phone',
                true,
                'touchscreen',
            ],
            [
                'UCWEB/2.0 (Linux; U; Opera Mini/7.1.32052/30.3697; en-US; U8860 Build/HuaweiU8860) U2/1.0.0 UCBrowser/10.1.5.583 Mobile',
                'U8860',
                'Honor',
                'Huawei',
                'Huawei',
                'Mobile Phone',
                true,
                'touchscreen',
            ],
            [
                'Mozilla/5.0 (compatible; MSIE 10.0; Windows Phone 8.0; Trident/6.0; IEMobile/10.0; ARM; Touch; HUAWEI; 4Afrika)',
                '4Afrika',
                '4Afrika',
                'Huawei',
                'Huawei',
                'Mobile Phone',
                null,
                null,
            ],
            [
                'UCWEB/2.0 (Linux; U; Opera Mini/7.1.32052/30.3697; www.tigo.com.gt; HUAWEI_Y330-U05) U2/1.0.0 UCBrowser/9.6.0.514 Mobile',
                'Y330-U05',
                'Y330-U05',
                'Huawei',
                'Huawei',
                'Mobile Phone',
                true,
                'touchscreen',
            ],
            [
                'UCWEB/2.0(Linux; U; Opera Mini/7.1.32052/30.3697; en-US; HUAWEI Y625-U51 Build/HUAWEIY625-U51) U2/1.0.0 UCBrowser/10.7.0.636 Mobile',
                'Y625-U51',
                'Ascend Y625',
                'Huawei',
                'Huawei',
                'Mobile Phone',
                true,
                'touchscreen',
            ],
            [
                'Mozilla/5.0 (compatible; MSIE 10.0; Windows Phone 8.0; Trident/6.0; IEMobile/10.0; ARM; Touch; HUAWEI; W2-U00)',
                'W2-U00',
                'Ascend W2',
                'Huawei',
                'Huawei',
                'Mobile Phone',
                true,
                'touchscreen',
            ],
            [
                'Mozilla/5.0 (Linux; Android 6.0; HUAWEI VNS-L31 Build/HUAWEIVNS-L31) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/52.0.2743.98 Mobile Safari/537.36',
                'VNS-L31',
                'P9 Lite',
                'Huawei',
                'Huawei',
                'Mobile Phone',
                true,
                'touchscreen',
            ],
            [
                'Mozilla/5.0 (Linux; U; Android 4.2.2; en-us; HUAWEI G750-U10 Build/HuaweiG750-U10) AppleWebKit/534.30 (KHTML, like Gecko) Version/4.0 Mobile Safari/534.30',
                'G750-U10',
                'Honor 3X',
                'Huawei',
                'Huawei',
                'Mobile Phone',
                true,
                'touchscreen',
            ],
            [
                'Mozilla/5.0 (Linux; U; Android 4.1.2; tr-tr; MediaPad 7 Youth Build/HuaweiMediaPad) AppleWebKit/534.30 (KHTML, like Gecko) Version/4.0 Safari/534.30',
                'MediaPad 7 Youth',
                'MediaPad 7 Youth',
                'Huawei',
                'Huawei',
                'Tablet',
                true,
                'touchscreen',
            ],
            [
                'Mozilla/5.0 (Linux; U; Android 4.2.2; fa-ir; HUAWEI G730-U10 Build/HuaweiG730-U10) AppleWebKit/534.30 (KHTML, like Gecko) Version/4.0 Mobile Safari/534.30',
                'G730-U10',
                'Ascend G730',
                'Huawei',
                'Huawei',
                'Mobile Phone',
                true,
                'touchscreen',
            ],
            [
                'Mozilla/5.0 (Linux; Android 6.0; PE-TL10 Build/HuaweiPE-TL10) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/52.0.2743.98 Mobile Safari/537.36',
                'PE-TL10',
                'Honor 6 Plus 4G',
                'Huawei',
                'Huawei',
                'Mobile Phone',
                true,
                'touchscreen',
            ],
            [
                'Mozilla/5.0 (Linux; U; Android 4.3; cs-cz; HUAWEI G6-L11 Build/HuaweiG6-L11) AppleWebKit/534.30 (KHTML, like Gecko) Version/4.0 Mobile Safari/534.30',
                'G6-L11',
                'Ascend G6 4G',
                'Huawei',
                'Huawei',
                'Mobile Phone',
                true,
                'touchscreen',
            ],
            [
                'Mozilla/5.0 (Linux; U; Android 4.3; de-de; HUAWEI P7 mini Build/HuaweiG6-L11C02B119) AppleWebKit/534.30 (KHTML, like Gecko) Version/4.0 Mobile Safari/534.30',
                'P7 Mini',
                'Ascend P7 Mini',
                'Huawei',
                'Huawei',
                'Mobile Phone',
                true,
                'touchscreen',
            ],
            [
                'Mozilla/5.0 (Linux; Android 7.0; Nexus 6P Build/NPD90G) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/52.0.2743.98 Mobile Safari/537.36',
                'Nexus 6P',
                'Nexus 6P',
                'Huawei',
                'Google',
                'Mobile Phone',
                true,
                'touchscreen',
            ],
            [
                'Mozilla/5.0 (Linux; Android 6.0; EVA-L09 Build/HUAWEIEVA-L09; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/52.0.2743.98 Mobile Safari/537.36',
                'EVA-L09',
                'EVA-L09',
                'Huawei',
                'Huawei',
                'Mobile Phone',
                true,
                'touchscreen',
            ],
            [
                'Mozilla/5.0 (Linux; Android 4.1.2; MediaPad 10 LINK Build/HuaweiMediaPad) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/31.0.1650.59 Safari/537.36',
                'S7-301w',
                'MediaPad 10 Link',
                'Huawei',
                'Huawei',
                'Tablet',
                true,
                'touchscreen',
            ],
            [
                'Mozilla/5.0 (Linux; Android 4.1.2; MediaPad 10 LINK+ Build/HuaweiMediaPad) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/31.0.1650.59 Safari/537.36',
                'MediaPad 10+',
                'MediaPad 10 Link+',
                'Huawei',
                'Huawei',
                'Tablet',
                true,
                'touchscreen',
            ],
            [
                'Mozilla/5.0 (Linux; Android 4.4.2; HUAWEI P7-L10 Build/HuaweiP7-L10) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/52.0.2743.98 Mobile Safari/537.36',
                'P7-L10',
                'Ascend P7',
                'Huawei',
                'Huawei',
                'Mobile Phone',
                true,
                'touchscreen',
            ],
            [
                'Mozilla/5.0 (Linux; Android 5.1.1; HUAWEI SCL-L01 Build/HuaweiSCL-L01) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/52.0.2743.98 Mobile Safari/537.36',
                'SCL-L01',
                'Y6',
                'Huawei',
                'Huawei',
                'Mobile Phone',
                true,
                'touchscreen',
            ],
            [
                'Mozilla/5.0 (;;; en-us; Huawei-U8651S Build/U8651SV100R001USAC85B843) AppleWebKit/533.1 (KHTML, like Gecko) Version/4.0 Mobile Safari/533.1',
                'U8651S',
                'Summit',
                'Huawei',
                'T-Mobile',
                'Mobile Phone',
                true,
                'touchscreen',
            ],
            [
                'Mozilla/5.0 (;;; en-us; Huawei-U8651T Build/U8651SV100R001USAC85B843) AppleWebKit/533.1 (KHTML, like Gecko) Version/4.0 Mobile Safari/533.1',
                'U8651T',
                'Prism',
                'Huawei',
                'T-Mobile',
                'Mobile Phone',
                true,
                'touchscreen',
            ],
            [
                'Mozilla/5.0 (;;; en-us; Huawei-U8651 Build/U8651SV100R001USAC85B843) AppleWebKit/533.1 (KHTML, like Gecko) Version/4.0 Mobile Safari/533.1',
                'U8651',
                'Prism',
                'Huawei',
                'T-Mobile',
                'Mobile Phone',
                true,
                'touchscreen',
            ],
            [
                'Mozilla/5.0 (Linux; Android 4.1.2; MediaPad 10 FHD Build/HuaweiMediaPad) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/35.0.1916.138 Safari/537.36 OPR/22.0.1485.78487',
                'MediaPad 10 FHD',
                'MediaPad 10 FHD',
                'Huawei',
                'Huawei',
                'Tablet',
                true,
                'touchscreen',
            ],
            [
                'Mozilla/5.0 (Linux; Android 4.0.4; HUAWEI U8950-1 Build/HuaweiU8950-1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/32.0.1700.72 Mobile Safari/537.36 OPR/19.0.1340.69518',
                'U8950-1',
                'Honor Pro',
                'Huawei',
                'Huawei',
                'Mobile Phone',
                true,
                'touchscreen',
            ],
            [
                'Mozilla/5.0 (Linux; Android 5.0.2; Huawei Y511 Build/JDQ39) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/40.0.2214.109 Mobile Safari/537.36',
                'Y511',
                'Ascend Y511',
                'Huawei',
                'Huawei',
                'Mobile Phone',
                true,
                'touchscreen',
            ],
            [
                'Mozilla/5.0 (Linux; Android 4.2.2; HUAWEI Y320-U30 Build/HUAWEIY320-U30) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/42.0.2311.111 Mobile Safari/537.36',
                'Y320-U30',
                'Ascend Y320',
                'Huawei',
                'Huawei',
                'Mobile Phone',
                true,
                'touchscreen',
            ],
            [
                'Mozilla/5.0 (Linux; Android 4.2.2; H30-U10 Build/H30-U10-???) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/40.0.2214.109 Mobile Safari/537.36',
                'H30-U10',
                'Honor 3C',
                'Huawei',
                'Huawei',
                'Mobile Phone',
                true,
                'touchscreen',
            ],
            [
                'Mozilla/5.0 (Linux; Android 4.2.2; HUAWEI Y330-U11 Build/HuaweiY330-U11) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/35.0.1916.138 Mobile Safari/537.36 OPR/22.0.1485.81203',
                'Y330-U11',
                'Ascend Y330',
                'Huawei',
                'Huawei',
                'Mobile Phone',
                true,
                'touchscreen',
            ],
            [
                'Mozilla/5.0 (Linux;Android 5.0;HUAWEI ALE-21/ALE-L21HUNC150B130)AppleWebKit/537.36 (KHTML, likeGecko) Version/4.0 Chrome/37.0.0.0 Mobile Safari/537.36',
                'ALE 21',
                'P8 Lite',
                'Huawei',
                'Huawei',
                'Mobile Phone',
                true,
                'touchscreen',
            ],
            [
                'Mozilla/5.0 (Linux; Android 6.0.1; KIW-L21 Build/HONORKIW-L21) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.94 Mobile Safari/537.36 OPR/37.0.2192.105088',
                'KIW-L21',
                'Honor 5X',
                'Huawei',
                'Huawei',
                'Mobile Phone',
                true,
                'touchscreen',
            ],
            [
                'Mozilla/5.0 (Linux; Android 4.1.1; HUAWEI Y300-0100 Build/HuaweiY300-0100) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/52.0.2743.98 Mobile Safari/537.36',
                'Y300',
                'Ascend Y 300',
                'Huawei',
                'Huawei',
                'Mobile Phone',
                true,
                'touchscreen',
            ],
            [
                'Mozilla/5.0 (Linux; Android 5.1; zh-cn; HUAWEI TAG-AL00 Build/HUAWEITAG-AL00) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/39.0.0.0 Mobile Safari/537.36',
                'TAG-AL00',
                'Enjoy 5S',
                'Huawei',
                'Huawei',
                'Mobile Phone',
                true,
                'touchscreen',
            ],
            [
                'Mozilla/5.0 (Linux; Android 6.0; HUAWEI GRA-L09 Build/HUAWEIGRA-L09) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/53.0.2785.97 Mobile Safari/537.36',
                'GRA-L09',
                'P8',
                'Huawei',
                'Huawei',
                'Mobile Phone',
                true,
                'touchscreen',
            ],
            [
                'Mozilla/5.0 (Linux; Android 4.3; S8-701w Build/HuaweiMediaPad) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/52.0.2743.98 Safari/537.36',
                'S8-701W',
                'Honor T1',
                'Huawei',
                'Huawei',
                'Tablet',
                true,
                'touchscreen',
            ],
            [
                'Mozilla/5.0 (Linux; Android 5.1.1; HUAWEI MT7-TL10 Build/HuaweiMT7-TL10) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/52.0.2743.98 Mobile Safari/537.36',
                'MT7-TL10',
                'Ascend Mate 7',
                'Huawei',
                'Huawei',
                'Mobile Phone',
                true,
                'touchscreen',
            ],
            [
                'Mozilla/5.0 (Linux; Android 6.0; HUAWEI MT7-L09 Build/HuaweiMT7-L09) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/53.0.2785.124 Mobile Safari/537.36',
                'MT7-L09',
                'MT7-L09',
                'Huawei',
                'Huawei',
                'Mobile Phone',
                true,
                'touchscreen',
            ],
            [
                'Mozilla/5.0 (compatible; MSIE 10.0; Windows Phone 8.0; Trident/6.0; IEMobile/10.0; ARM; Touch; HUAWEI; W1-U00)',
                'W1-U00',
                'Ascend W1',
                'Huawei',
                'Huawei',
                'Mobile Phone',
                true,
                'touchscreen',
            ],
            [
                'UCWEB/2.0 (MIDP-2.0; U; Adr 4.1.2; ru; HUAWEI_D2-0082) U2/1.0.0 UCBrowser/8.9.2.373 U2/1.0.0 Mobile',
                'D2-0082',
                'Ascend D2',
                'Huawei',
                'Huawei',
                'Mobile Phone',
                true,
                'touchscreen',
            ],
            [
                'Mozilla/5.0 (Linux; U; Android 4.2.2; zh-CN; HUAWEI Y320-U10 Build/HUAWEIY320-U10) AppleWebKit/534.31 (KHTML, like Gecko) UCBrowser/9.1.0.297 U3/0.8.0 Mobile Safari/534.31',
                'Y320-U10',
                'Ascend Y320',
                'Huawei',
                'Huawei',
                'Mobile Phone',
                true,
                'touchscreen',
            ],
            [
                'Mozilla/5.0 (Linux; U; Android 4.2.2; ru-ru; HUAWEI G750-T00 Build/HuaweiG750-T00) AppleWebKit/534.30 (KHTML, like Gecko) Version/4.0 Mobile Safari/534.30 [FB_IAB/FB4A;FBAV/31.0.0.19.13;]',
                'G750-T00',
                'Honor 3X',
                'Huawei',
                'Huawei',
                'Mobile Phone',
                true,
                'touchscreen',
            ],
            [
                'UCWEB/2.0 (MIDP-2.0; U; zh-CN; P7mini) U2/1.0.0 UCBrowser/3.4.3.532  U2/1.0.0 Mobile',
                'P7 Mini',
                'Ascend P7 Mini',
                'Huawei',
                'Huawei',
                'Mobile Phone',
                true,
                'touchscreen',
            ],
            [
                'Mozilla/5.0 (Linux; Android 4.2.2; HUAWEI Y600-U00 Build/HUAWEIY600-U00) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/36.0.1985.135 Mobile Safari/537.36',
                'Y600-U00',
                'Ascend Y600',
                'Huawei',
                'Huawei',
                'Mobile Phone',
                true,
                'touchscreen',
            ],
            [
                'Mozilla/5.0 (Linux; Android 4.4.2; HUAWEI HN3-U01 Build/HuaweiHN3-U01) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/30.0.0.0 Mobile Safari/537.36 ACHEETAHI/2100501074',
                'HN3-U01',
                'Honor 3',
                'Huawei',
                'Huawei',
                'Mobile Phone',
                true,
                'touchscreen',
            ],
            [
                'Mozilla/5.0 (Linux; Android 4.4.4; Y635-L21 Build/HuaweiY635-L21) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/33.0.0.0 Mobile Safari/537.36',
                'Y635-L21',
                'Ascend Y635',
                'Huawei',
                'Huawei',
                'Mobile Phone',
                true,
                'touchscreen',
            ],
            [
                'Mozilla/5.0 (Linux; Android 4.4.2; T1-701u Build/HuaweiMediaPad) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.89 Safari/537.36',
                'T1-701U',
                'MediaPad T1 7.0 3G',
                'Huawei',
                'Huawei',
                'Tablet',
                true,
                'touchscreen',
            ],
            [
                'Mozilla/5.0 (Linux; Android 4.4.4; HUAWEI Y550-L01 Build/HuaweiY550-L01) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/33.0.0.0 Mobile Safari/537.36',
                'Y550-L01',
                'Ascend Y550',
                'Huawei',
                'Huawei',
                'Mobile Phone',
                true,
                'touchscreen',
            ],
            [
                'Mozilla/5.0 (Linux; Android 4.4.4; G7-L01 Build/HuaweiG7-L01) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.89 Mobile Safari/537.36',
                'G7-L01',
                'Ascend G7',
                'Huawei',
                'Huawei',
                'Mobile Phone',
                true,
                'touchscreen',
            ],
            [
                'Mozilla/5.0 (Linux; U; Android 4.3; de-de; HUAWEI Y530-U00 Build/HuaweiY530-U00C150B188) AppleWebKit/534.30 (KHTML, like Gecko) Version/4.0 Mobile Safari/534.30',
                'Y530-U00',
                'Ascend Y530',
                'Huawei',
                'Huawei',
                'Mobile Phone',
                true,
                'touchscreen',
            ],
            [
                'Mozilla/5.0 (Linux; Android 4.4.4; T1-A21L Build/HuaweiMediaPad) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/33.0.0.0 Safari/537.36',
                'T1-A21L',
                'MediaPad T1 10.0 LTE',
                'Huawei',
                'Huawei',
                'FonePad',
                true,
                'touchscreen',
            ],
            [
                'Mozilla/5.0 (Linux; Android 5.0.1; ALE-L21 Build/HuaweiALE-L21) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/37.0.0.0 Mobile Safari/537.36',
                'ALE-L21',
                'P8 Lite',
                'Huawei',
                'Huawei',
                'Mobile Phone',
                true,
                'touchscreen',
            ],
            [
                'Mozilla/5.0 (Linux; U; Android 4.4.2; zh-CN; HUAWEI P7-L09 Build/HuaweiP7-L09) AppleWebKit/534.30 (KHTML, like Gecko) Version/4.0 UCBrowser/10.3.1.549 U3/0.8.0 Mobile Safari/534.30',
                'P7-L09',
                'Ascend P7',
                'Huawei',
                'Huawei',
                'Mobile Phone',
                true,
                'touchscreen',
            ],
            [
                'Mozilla/5.0 (Linux; Android 4.4.4; G7-L01 Build/HuaweiG7-L01) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.89 Mobile Safari/537.36',
                'G7-L01',
                'Ascend G7',
                'Huawei',
                'Huawei',
                'Mobile Phone',
                true,
                'touchscreen',
            ],
            [
                'Mozilla/5.0 (Linux; Android 4.4.2; HUAWEI Y336-U02 Build/HUAWEIY336-U02) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.89 Mobile Safari/537.36',
                'Y336-U02',
                'Y3',
                'Huawei',
                'Huawei',
                'Mobile Phone',
                true,
                'touchscreen',
            ],
            [
                'Mozilla/5.0 (Linux; U; Android 4.0.3; de-de; HUAWEI U8666E Build/HuaweiU8666E) AppleWebKit/534.30 (KHTML, like Gecko) Version/4.0 Mobile Safari/534.30',
                'U8666E',
                'Ascend Y201 Pro',
                'Huawei',
                'Huawei',
                'Mobile Phone',
                true,
                'touchscreen',
            ],
            [
                'Mozilla/5.0 (Linux; U; Android 4.0.3; de-de; HUAWEI U8666 Build/HuaweiU8666E) AppleWebKit/534.30 (KHTML, like Gecko) Version/4.0 Mobile Safari/534.30',
                'U8666',
                'Ascend Y201 Pro',
                'Huawei',
                'Huawei',
                'Mobile Phone',
                true,
                'touchscreen',
            ],
            [
                'Mozilla/5.0 (Linux; Android 5.1.1; HUAWEI M2-A01L Build/HUAWEIM2-A01L) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/39.0.0.0 Safari/537.36',
                'M2-A01L',
                'MediaPad M2 10.0',
                'Huawei',
                'Huawei',
                'Tablet',
                true,
                'touchscreen',
            ],
            [
                'Mozilla/5.0 (Linux; Android 5.1; HUAWEI RIO-L01 Build/HuaweiRIO-L01) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.89 Mobile Safari/537.36',
                'RIO-L01',
                'GX8',
                'Huawei',
                'Huawei',
                'Mobile Phone',
                true,
                'touchscreen',
            ],
            [
                'Mozilla/5.0 (Linux; Android 5.1.1; HUAWEI CRR-L09 Build/HUAWEICRR-L09C150B121) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.89 Mobile Safari/537.36',
                'CRR-L09',
                'Mate S',
                'Huawei',
                'Huawei',
                'Mobile Phone',
                true,
                'touchscreen',
            ],
            [
                'Mozilla/5.0 (Linux; Android 4.4.4; T1-A21w Build/HuaweiMediaPad) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.89 Safari/537.36',
                'T1-A21W',
                'MediaPad T1 10.0 WiFi',
                'Huawei',
                'Huawei',
                'FonePad',
                true,
                'touchscreen',
            ],
            [
                'Mozilla/5.0 (Linux; Android 5.1.1; HUAWEI ATH-UL01 Build/HUAWEIATH-UL01) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/39.0.0.0 Mobile Safari/537.36',
                'ATH-UL01',
                'Honor 7i',
                'Huawei',
                'Huawei',
                'Mobile Phone',
                true,
                'touchscreen',
            ],
            [
                'Mozilla/5.0 (Linux; Android 4.4.2; HUAWEI P6-U06 Build/HuaweiP6-U06) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.89 Mobile Safari/537.36',
                'P6-U06',
                'Ascend P6',
                'Huawei',
                'Huawei',
                'Mobile Phone',
                true,
                'touchscreen',
            ],
            [
                'Mozilla/5.0 (Linux; Android 4.1.1; HUAWEI G510-0100 Build/HuaweiG510-0100) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.95 Mobile Safari/537.36',
                'G510-0100',
                'Ascend G510',
                'Huawei',
                'Huawei',
                'Mobile Phone',
                true,
                'touchscreen',
            ],
            [
                'Mozilla/5.0 (Linux; U; Android 4.4.2; de-de; HUAWEI Y625-U51 Build/HUAWEIY625-U51) AppleWebKit/534.30 (KHTML, like Gecko) Version/4.0 Mobile Safari/534.30',
                'Y625-U51',
                'Ascend Y625',
                'Huawei',
                'Huawei',
                'Mobile Phone',
                true,
                'touchscreen',
            ],
            [
                'Mozilla/5.0 (Linux; Android 5.1.1; HUAWEI SCL-L21 Build/HuaweiSCL-L21) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/39.0.0.0 Mobile Safari/537.36',
                'SCL-L21',
                'Y6',
                'Huawei',
                'Huawei',
                'Mobile Phone',
                true,
                'touchscreen',
            ],
            [
                'Mozilla/5.0 (Linux; Android 4.4.2; HUAWEI Y360-U61 Build/HUAWEIY360-U61) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/40.0.2214.109 Mobile Safari/537.36',
                'Y360-U61',
                'Ascend Y360',
                'Huawei',
                'Huawei',
                'Mobile Phone',
                true,
                'touchscreen',
            ],
            [
                'Mozilla/5.0 (Linux; Android 6.0; HUAWEI NXT-L29 Build/HUAWEINXT-L29) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.89 Mobile Safari/537.36',
                'NXT-L29',
                'Mate 8',
                'Huawei',
                'Huawei',
                'Mobile Phone',
                true,
                'touchscreen',
            ],
            [
                'Mozilla/5.0 (Linux; Android 5.0; GEM-701L Build/HUAWEIGEM-701L) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/37.0.0.0 Mobile Safari/537.36',
                'GEM-701L',
                'MediaPad X2 Premium Dual-SIM LTE',
                'Huawei',
                'Huawei',
                'FonePad',
                true,
                'touchscreen',
            ],
            [
                'Mozilla/5.0 (Linux; Android 4.4.4; G620S-L01 Build/HuaweiG620S-L01) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.89 Mobile Safari/537.36',
                'G620S-L01',
                'Ascend G620S',
                'Huawei',
                'Huawei',
                'Mobile Phone',
                true,
                'touchscreen',
            ],
            [
                'Mozilla/5.0 (Linux; Android 4.4.2; CHC-U01 Build/HuaweiCHC-U01) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/30.0.0.0 Mobile Safari/537.36',
                'CHC-U01',
                'G Play Mini',
                'Huawei',
                'Huawei',
                'Mobile Phone',
                true,
                'touchscreen',
            ],
            [
                'Mozilla/5.0 (Linux; Android 4.4.2; HUAWEI G6-U10 Build/HuaweiG6-U10) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/30.0.0.0 Mobile Safari/537.36',
                'G6-U10',
                'Ascend G6 3G',
                'Huawei',
                'Huawei',
                'Mobile Phone',
                true,
                'touchscreen',
            ],
            [
                'Mozilla/5.0 (Linux; Android 6.0; HUAWEI NXT-AL10 Build/HUAWEINXT-AL10) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.89 Mobile Safari/537.36',
                'NXT-AL10',
                'Mate 8',
                'Huawei',
                'Huawei',
                'Mobile Phone',
                true,
                'touchscreen',
            ],
            [
                'Mozilla/5.0 (Linux; U; Android 5.1.1; de-de; HUAWEI Y560-L01 Build/HUAWEIY560-L01) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Mobile Safari/537.36',
                'Y560-L01',
                'Ascend Y560',
                'Huawei',
                'Huawei',
                'Mobile Phone',
                true,
                'touchscreen',
            ],
            [
                'Mozilla/5.0 (Linux; U; Android 4.0.4; de-de; HUAWEI U8950N-1 Build/HuaweiU8950N-1) AppleWebKit/534.30 (KHTML, like Gecko) Version/4.0 Mobile Safari/534.30',
                'U8950N-1',
                'Ascend G600',
                'Huawei',
                'Huawei',
                'Mobile Phone',
                true,
                'touchscreen',
            ],
            [
                'Mozilla/5.0 (Linux; U; Android 4.0.4; de-de; HUAWEI U8950N Build/HuaweiU8950N) AppleWebKit/534.30 (KHTML, like Gecko) Version/4.0 Mobile Safari/534.30',
                'U8950N',
                'Ascend G600',
                'Huawei',
                'Huawei',
                'Mobile Phone',
                true,
                'touchscreen',
            ],
            [
                'Mozilla/5.0 (Linux; U; Android 4.0.4; de-de; HUAWEI U8950D Build/HuaweiU8950D) AppleWebKit/534.30 (KHTML, like Gecko) Version/4.0 Mobile Safari/534.30',
                'U8950D',
                'Ascend G600',
                'Huawei',
                'Huawei',
                'Mobile Phone',
                true,
                'touchscreen',
            ],
            [
                'Mozilla/5.0 (Linux; U; Android 4.0.4; de-de; HUAWEI U8950 Build/HuaweiU8950) AppleWebKit/534.30 (KHTML, like Gecko) Version/4.0 Mobile Safari/534.30',
                'U8950',
                'Ascend G600',
                'Huawei',
                'Huawei',
                'Mobile Phone',
                true,
                'touchscreen',
            ],
            [
                'Mozilla/5.0 (Linux; Android 4.1.2; HUAWEI G525-U00 Build/HuaweiG525-U00) AppleWebKit/537.31 (KHTML, like Gecko) Chrome/26.0.1410.58 Mobile Safari/537.31',
                'G525-U00',
                'Ascend G525',
                'Huawei',
                'Huawei',
                'Mobile Phone',
                true,
                'touchscreen',
            ],
            [
                'Mozilla/5.0 (Linux; Android 4.2.1; HUAWEI G610-U20 Build/HuaweiG610-U20) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/49.0.2623.105 Mobile Safari/537.36',
                'G610-U20',
                'Ascend G610',
                'Huawei',
                'Huawei',
                'Mobile Phone',
                true,
                'touchscreen',
            ],
            [
                'Mozilla/5.0 (Linux; Android 5.0.1; ALE-L02 Build/HuaweiALE-L02) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.89 Mobile Safari/537.36',
                'ALE-L02',
                'P8 Lite',
                'Huawei',
                'Huawei',
                'Mobile Phone',
                true,
                'touchscreen',
            ],
            [
                'Mozilla/5.0 (Linux; Android 4.2.2; HUAWEI MT1-U06 Build/HuaweiMT1-U06) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.89 Mobile Safari/537.36',
                'MT1-U06',
                'Ascend Mate',
                'Huawei',
                'Huawei',
                'Mobile Phone',
                true,
                'touchscreen',
            ],
            [
                'Mozilla/5.0 (Linux; U; Android 4.1.2; de-de; HUAWEI P2-6011 Build/HuaweiP2-6011) AppleWebKit/534.30 (KHTML, like Gecko) Version/4.0 Mobile Safari/534.30',
                'P2-6011',
                'Ascend P2',
                'Huawei',
                'Huawei',
                'Mobile Phone',
                true,
                'touchscreen',
            ],
            [
                'Mozilla/5.0 (Linux; U; Android 4.3; de-de; MediaPad T1 8.0 Build/HuaweiMediaPad) AppleWebKit/534.30 (KHTML, like Gecko) Version/4.0 Safari/534.30',
                'S8-701u',
                'MediaPad T1 8.0',
                'Huawei',
                'Huawei',
                'Tablet',
                true,
                'touchscreen',
            ],
            [
                'Mozilla/5.0 (Linux; U; Android 4.0.3; de-de; U9200 Build/HuaweiU9200) AppleWebKit/534.30 (KHTML, like Gecko) Version/4.0 Mobile Safari/534.30',
                'U9200',
                'Ascend P1',
                'Huawei',
                'Huawei',
                'Mobile Phone',
                true,
                'touchscreen',
            ],
            [
                'Mozilla/5.0 (Linux; U; Android 2.3.6; de-de; U8860 Build/HuaweiU8860) AppleWebKit/533.1 (KHTML, like Gecko) Version/4.0 Mobile Safari/533.1',
                'U8860',
                'Honor',
                'Huawei',
                'Huawei',
                'Mobile Phone',
                true,
                'touchscreen',
            ],
            [
                'Mozilla/5.0 (Linux; U; Android 4.3; de-de; G630-U20 Build/HuaweiG630-U20) AppleWebKit/534.30 (KHTML, like Gecko) Version/4.0 Mobile Safari/534.30',
                'G630-U20',
                'Ascend G630',
                'Huawei',
                'Huawei',
                'Mobile Phone',
                true,
                'touchscreen',
            ],
            [
                'Mozilla/5.0 (Linux; Android 5.1.1; HUAWEI M2-A01W Build/HUAWEIM2-A01W) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.89 Safari/537.36',
                'M2-A01W',
                'MediaPad M2 10.0',
                'Huawei',
                'Huawei',
                'Tablet',
                true,
                'touchscreen',
            ],
            [
                'Mozilla/5.0 (Linux; U; Android 4.4.2; de-de; MediaPad M1 8.0 Build/HuaweiMediaPad) AppleWebKit/534.30 (KHTML, like Gecko) Version/4.0 Chrome/30.0.0.0 Safari/534.30',
                'MediaPad M1 8.0',
                'MediaPad M1 8.0',
                'Huawei',
                'Huawei',
                'Tablet',
                true,
                'touchscreen',
            ],
            [
                'Mozilla/5.0 (Linux; Android 5.1.1; HUAWEI M2-801W Build/HUAWEIM2-801W) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/49.0.2623.91 Safari/537.36',
                'M2-801W',
                'MediaPad M2 8.0',
                'Huawei',
                'Huawei',
                'Tablet',
                true,
                'touchscreen',
            ],
            [
                'Mozilla/5.0 (Linux; U; Android 4.2.2; de-de; HUAWEI Y600-U20 Build/HUAWEIY600-U20) AppleWebKit/534.30 (KHTML, like Gecko) Version/4.0 Mobile Safari/534.30',
                'Y600-U20',
                'Ascend Y600',
                'Huawei',
                'Huawei',
                'Mobile Phone',
                true,
                'touchscreen',
            ],
            [
                'Mozilla/5.0 (Linux; Android 4.4.2; Hol-U19 Build/HUAWEIHol-U19) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/49.0.2623.105 Mobile Safari/537.36',
                'HOL-U19',
                'Honor Holly',
                'Huawei',
                'Huawei',
                'Mobile Phone',
                true,
                'touchscreen',
            ],
            [
                'Mozilla/5.0 (Linux; U; Android 4.2.1; de-de; HUAWEI G700-U10 Build/HuaweiG700-U10) AppleWebKit/534.30 (KHTML, like Gecko) Version/4.0 Mobile Safari/534.30 ACHEETAHI/2100502044',
                'G700-U10',
                'Ascend G700',
                'Huawei',
                'Huawei',
                'Mobile Phone',
                true,
                'touchscreen',
            ],
        ];
    }
}