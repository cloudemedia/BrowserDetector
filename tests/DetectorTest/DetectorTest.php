<?php
/**
 * This file is part of the browser-detector package.
 *
 * Copyright (c) 2012-2020, Thomas Mueller <mimmi20@live.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types = 1);
namespace BrowserDetectorTest;

use BrowserDetector\Cache\CacheInterface;
use BrowserDetector\Detector;
use BrowserDetector\Loader\NotFoundException;
use BrowserDetector\Parser\BrowserParserInterface;
use BrowserDetector\Parser\DeviceParserInterface;
use BrowserDetector\Parser\EngineParserInterface;
use BrowserDetector\Parser\PlatformParserInterface;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use UaNormalizer\Normalizer\NormalizerInterface;
use UaRequest\Constants;
use UaRequest\GenericRequestFactory;
use UaResult\Browser\BrowserInterface;
use UaResult\Device\DeviceInterface;
use UaResult\Engine\EngineInterface;
use UaResult\Os\OsInterface;
use UaResult\Result\Result;
use UaResult\Result\ResultInterface;
use Zend\Diactoros\ServerRequestFactory;

final class DetectorTest extends TestCase
{
    /**
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \PHPUnit\Framework\Exception
     * @throws \InvalidArgumentException
     * @throws \UnexpectedValueException
     *
     * @return void
     */
    public function testGetBrowserFromUaOld(): void
    {
        $useragent           = 'testagent';
        $normalizedUseragent = 'normalized testagent';
        $logger              = $this->getMockBuilder(LoggerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $logger
            ->expects(self::once())
            ->method('debug');
        $logger
            ->expects(self::never())
            ->method('info');
        $logger
            ->expects(self::never())
            ->method('notice');
        $logger
            ->expects(self::never())
            ->method('warning');
        $logger
            ->expects(self::never())
            ->method('error');
        $logger
            ->expects(self::never())
            ->method('critical');
        $logger
            ->expects(self::never())
            ->method('alert');
        $logger
            ->expects(self::never())
            ->method('emergency');

        $device = $this->createMock(DeviceInterface::class);
        $os     = $this->createMock(OsInterface::class);

        $deviceParser = $this->getMockBuilder(DeviceParserInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $deviceParser
            ->expects(self::once())
            ->method('parse')
            ->with($normalizedUseragent)
            ->willReturn([$device, $os]);

        $platformParser = $this->getMockBuilder(PlatformParserInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $platformParser
            ->expects(self::never())
            ->method('parse');

        $browser = $this->createMock(BrowserInterface::class);
        $engine  = $this->createMock(EngineInterface::class);

        $browserParser = $this->getMockBuilder(BrowserParserInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $browserParser
            ->expects(self::once())
            ->method('parse')
            ->with($normalizedUseragent)
            ->willReturn([$browser, $engine]);

        $engineParser = $this->getMockBuilder(EngineParserInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $engineParser
            ->expects(self::never())
            ->method('parse');
        $engineParser
            ->expects(self::never())
            ->method('load');

        $cache = $this->getMockBuilder(CacheInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $cache
            ->expects(self::once())
            ->method('hasItem')
            ->willReturn(false);
        $cache
            ->expects(self::never())
            ->method('getItem');
        $cache
            ->expects(self::once())
            ->method('setItem')
            ->willReturn(false);

        $normalizer = $this->getMockBuilder(NormalizerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $normalizer
            ->expects(self::exactly(3))
            ->method('normalize')
            ->with($useragent)
            ->willReturn($normalizedUseragent);

        /** @var \Psr\Log\LoggerInterface $logger */
        /** @var CacheInterface $cache */
        /** @var DeviceParserInterface $deviceParser */
        /** @var PlatformParserInterface $platformParser */
        /** @var BrowserParserInterface $browserParser */
        /** @var EngineParserInterface $engineParser */
        /** @var NormalizerInterface $normalizer */
        $object = new Detector(
            $logger,
            $cache,
            $deviceParser,
            $platformParser,
            $browserParser,
            $engineParser,
            $normalizer
        );

        /** @var \UaResult\Result\Result $result */
        $result = $object->getBrowser($useragent);

        self::assertInstanceOf(ResultInterface::class, $result);
        self::assertSame($device, $result->getDevice());
        self::assertSame($os, $result->getOs());
        self::assertSame($browser, $result->getBrowser());
        self::assertSame($engine, $result->getEngine());
    }

    /**
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \PHPUnit\Framework\Exception
     * @throws \InvalidArgumentException
     * @throws \UnexpectedValueException
     *
     * @return void
     */
    public function testGetBrowserFromGenericRequest(): void
    {
        $useragent           = 'testagent';
        $normalizedUseragent = 'normalized testagent';
        $logger              = $this->getMockBuilder(LoggerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $logger
            ->expects(self::once())
            ->method('debug');
        $logger
            ->expects(self::never())
            ->method('info');
        $logger
            ->expects(self::never())
            ->method('notice');
        $logger
            ->expects(self::never())
            ->method('warning');
        $logger
            ->expects(self::never())
            ->method('error');
        $logger
            ->expects(self::never())
            ->method('critical');
        $logger
            ->expects(self::never())
            ->method('alert');
        $logger
            ->expects(self::never())
            ->method('emergency');

        $device = $this->createMock(DeviceInterface::class);
        $os     = $this->createMock(OsInterface::class);

        $deviceParser = $this->getMockBuilder(DeviceParserInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $deviceParser
            ->expects(self::once())
            ->method('parse')
            ->with($normalizedUseragent)
            ->willReturn([$device, $os]);

        $platformParser = $this->getMockBuilder(PlatformParserInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $platformParser
            ->expects(self::never())
            ->method('parse');

        $browser = $this->createMock(BrowserInterface::class);
        $engine  = $this->createMock(EngineInterface::class);

        $browserParser = $this->getMockBuilder(BrowserParserInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $browserParser
            ->expects(self::once())
            ->method('parse')
            ->with($normalizedUseragent)
            ->willReturn([$browser, $engine]);

        $engineParser = $this->getMockBuilder(EngineParserInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $engineParser
            ->expects(self::never())
            ->method('parse');
        $engineParser
            ->expects(self::never())
            ->method('load');

        $cache = $this->getMockBuilder(CacheInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $cache
            ->expects(self::once())
            ->method('hasItem')
            ->willReturn(false);
        $cache
            ->expects(self::never())
            ->method('getItem');
        $cache
            ->expects(self::once())
            ->method('setItem')
            ->willReturn(false);

        $normalizer = $this->getMockBuilder(NormalizerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $normalizer
            ->expects(self::exactly(3))
            ->method('normalize')
            ->with($useragent)
            ->willReturn($normalizedUseragent);

        /** @var \Psr\Log\LoggerInterface $logger */
        /** @var CacheInterface $cache */
        /** @var DeviceParserInterface $deviceParser */
        /** @var PlatformParserInterface $platformParser */
        /** @var BrowserParserInterface $browserParser */
        /** @var EngineParserInterface $engineParser */
        /** @var NormalizerInterface $normalizer */
        $object = new Detector(
            $logger,
            $cache,
            $deviceParser,
            $platformParser,
            $browserParser,
            $engineParser,
            $normalizer
        );

        $message        = ServerRequestFactory::fromGlobals([Constants::HEADER_HTTP_USERAGENT => [$useragent]]);
        $requestFactory = new GenericRequestFactory();
        $request        = $requestFactory->createRequestFromPsr7Message($message);

        /** @var Result $result */
        $result = $object->__invoke($request);

        self::assertInstanceOf(ResultInterface::class, $result);
        self::assertSame($device, $result->getDevice());
        self::assertSame($os, $result->getOs());
        self::assertSame($browser, $result->getBrowser());
        self::assertSame($engine, $result->getEngine());
    }

    /**
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \PHPUnit\Framework\Exception
     * @throws \InvalidArgumentException
     * @throws \UnexpectedValueException
     *
     * @return void
     */
    public function testGetBrowserFromGenericRequest2(): void
    {
        $useragent = 'testagent';
        $logger    = $this->getMockBuilder(LoggerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $logger
            ->expects(self::exactly(2))
            ->method('debug');
        $logger
            ->expects(self::never())
            ->method('info');
        $logger
            ->expects(self::never())
            ->method('notice');
        $logger
            ->expects(self::never())
            ->method('warning');
        $logger
            ->expects(self::never())
            ->method('error');
        $logger
            ->expects(self::never())
            ->method('critical');
        $logger
            ->expects(self::never())
            ->method('alert');
        $logger
            ->expects(self::never())
            ->method('emergency');

        $deviceParser = $this->getMockBuilder(DeviceParserInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $deviceParser
            ->expects(self::never())
            ->method('parse');

        $platformParser = $this->getMockBuilder(PlatformParserInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $platformParser
            ->expects(self::never())
            ->method('parse');

        $browserParser = $this->getMockBuilder(BrowserParserInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $browserParser
            ->expects(self::never())
            ->method('parse');

        $engineParser = $this->getMockBuilder(EngineParserInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $engineParser
            ->expects(self::never())
            ->method('parse');
        $engineParser
            ->expects(self::never())
            ->method('load');

        $mockResult = $this->createMock(ResultInterface::class);

        $cache = $this->getMockBuilder(CacheInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $cache
            ->expects(self::exactly(2))
            ->method('hasItem')
            ->willReturn(true);
        $cache
            ->expects(self::exactly(2))
            ->method('getItem')
            ->willReturn($mockResult);
        $cache
            ->expects(self::never())
            ->method('setItem');

        $normalizer = $this->getMockBuilder(NormalizerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $normalizer
            ->expects(self::never())
            ->method('normalize');

        /** @var \Psr\Log\LoggerInterface $logger */
        /** @var CacheInterface $cache */
        /** @var DeviceParserInterface $deviceParser */
        /** @var PlatformParserInterface $platformParser */
        /** @var BrowserParserInterface $browserParser */
        /** @var EngineParserInterface $engineParser */
        /** @var NormalizerInterface $normalizer */
        $object = new Detector(
            $logger,
            $cache,
            $deviceParser,
            $platformParser,
            $browserParser,
            $engineParser,
            $normalizer
        );

        $message        = ServerRequestFactory::fromGlobals([Constants::HEADER_HTTP_USERAGENT => [$useragent]]);
        $requestFactory = new GenericRequestFactory();
        $request        = $requestFactory->createRequestFromPsr7Message($message);

        /** @var Result $result */
        $result = $object($request);

        self::assertInstanceOf(ResultInterface::class, $result);
        self::assertSame($mockResult, $result);

        /** @var Result $result2 */
        $result2 = $object->__invoke($message);

        self::assertSame($result, $result2);
    }

    /**
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \UnexpectedValueException
     *
     * @return void
     */
    public function testGetBrowserFromInvalid(): void
    {
        $logger = $this->getMockBuilder(LoggerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $logger
            ->expects(self::never())
            ->method('debug');
        $logger
            ->expects(self::never())
            ->method('info');
        $logger
            ->expects(self::never())
            ->method('notice');
        $logger
            ->expects(self::never())
            ->method('warning');
        $logger
            ->expects(self::never())
            ->method('error');
        $logger
            ->expects(self::never())
            ->method('critical');
        $logger
            ->expects(self::never())
            ->method('alert');
        $logger
            ->expects(self::never())
            ->method('emergency');

        $deviceParser = $this->getMockBuilder(DeviceParserInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $deviceParser
            ->expects(self::never())
            ->method('parse');

        $platformParser = $this->getMockBuilder(PlatformParserInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $platformParser
            ->expects(self::never())
            ->method('parse');

        $browserParser = $this->getMockBuilder(BrowserParserInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $browserParser
            ->expects(self::never())
            ->method('parse');

        $engineParser = $this->getMockBuilder(EngineParserInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $engineParser
            ->expects(self::never())
            ->method('parse');
        $engineParser
            ->expects(self::never())
            ->method('load');

        $cache = $this->getMockBuilder(CacheInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $cache
            ->expects(self::never())
            ->method('hasItem')
            ->willReturn(false);
        $cache
            ->expects(self::never())
            ->method('getItem');
        $cache
            ->expects(self::never())
            ->method('setItem')
            ->willReturn(false);

        $normalizer = $this->getMockBuilder(NormalizerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $normalizer
            ->expects(self::never())
            ->method('normalize');

        /** @var \Psr\Log\LoggerInterface $logger */
        /** @var CacheInterface $cache */
        /** @var DeviceParserInterface $deviceParser */
        /** @var PlatformParserInterface $platformParser */
        /** @var BrowserParserInterface $browserParser */
        /** @var EngineParserInterface $engineParser */
        /** @var NormalizerInterface $normalizer */
        $object = new Detector(
            $logger,
            $cache,
            $deviceParser,
            $platformParser,
            $browserParser,
            $engineParser,
            $normalizer
        );

        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('the request parameter has to be a string, an array or an instance of \Psr\Http\Message\MessageInterface');

        $object->__invoke(new \stdClass());
    }

    /**
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \PHPUnit\Framework\Exception
     * @throws \InvalidArgumentException
     * @throws \UnexpectedValueException
     *
     * @return void
     */
    public function testGetBrowserFromUa(): void
    {
        $useragent           = 'testagent';
        $normalizedUseragent = 'normalized testagent';
        $logger              = $this->getMockBuilder(LoggerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $logger
            ->expects(self::once())
            ->method('debug');
        $logger
            ->expects(self::never())
            ->method('info');
        $logger
            ->expects(self::never())
            ->method('notice');
        $logger
            ->expects(self::never())
            ->method('warning');
        $logger
            ->expects(self::never())
            ->method('error');
        $logger
            ->expects(self::never())
            ->method('critical');
        $logger
            ->expects(self::never())
            ->method('alert');
        $logger
            ->expects(self::never())
            ->method('emergency');

        $device = $this->createMock(DeviceInterface::class);
        $os     = $this->createMock(OsInterface::class);

        $deviceParser = $this->getMockBuilder(DeviceParserInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $deviceParser
            ->expects(self::once())
            ->method('parse')
            ->with($normalizedUseragent)
            ->willReturn([$device, $os]);

        $platformParser = $this->getMockBuilder(PlatformParserInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $platformParser
            ->expects(self::never())
            ->method('parse');

        $browser = $this->createMock(BrowserInterface::class);
        $engine  = $this->createMock(EngineInterface::class);

        $browserParser = $this->getMockBuilder(BrowserParserInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $browserParser
            ->expects(self::once())
            ->method('parse')
            ->with($normalizedUseragent)
            ->willReturn([$browser, $engine]);

        $engineParser = $this->getMockBuilder(EngineParserInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $engineParser
            ->expects(self::never())
            ->method('parse');
        $engineParser
            ->expects(self::never())
            ->method('load');

        $cache = $this->getMockBuilder(CacheInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $cache
            ->expects(self::once())
            ->method('hasItem')
            ->willReturn(false);
        $cache
            ->expects(self::never())
            ->method('getItem');
        $cache
            ->expects(self::once())
            ->method('setItem')
            ->willReturn(false);

        $normalizer = $this->getMockBuilder(NormalizerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $normalizer
            ->expects(self::exactly(3))
            ->method('normalize')
            ->with($useragent)
            ->willReturn($normalizedUseragent);

        /** @var \Psr\Log\LoggerInterface $logger */
        /** @var CacheInterface $cache */
        /** @var DeviceParserInterface $deviceParser */
        /** @var PlatformParserInterface $platformParser */
        /** @var BrowserParserInterface $browserParser */
        /** @var EngineParserInterface $engineParser */
        /** @var NormalizerInterface $normalizer */
        $object = new Detector(
            $logger,
            $cache,
            $deviceParser,
            $platformParser,
            $browserParser,
            $engineParser,
            $normalizer
        );

        /** @var \UaResult\Result\Result $result */
        $result = $object->__invoke($useragent);

        self::assertInstanceOf(ResultInterface::class, $result);
        self::assertSame($device, $result->getDevice());
        self::assertSame($os, $result->getOs());
        self::assertSame($browser, $result->getBrowser());
        self::assertSame($engine, $result->getEngine());
    }

    /**
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \PHPUnit\Framework\Exception
     * @throws \InvalidArgumentException
     * @throws \UnexpectedValueException
     *
     * @return void
     */
    public function testGetBrowserFromArray(): void
    {
        $useragent           = 'testagent';
        $normalizedUseragent = 'normalized testagent';
        $logger              = $this->getMockBuilder(LoggerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $logger
            ->expects(self::once())
            ->method('debug');
        $logger
            ->expects(self::never())
            ->method('info');
        $logger
            ->expects(self::never())
            ->method('notice');
        $logger
            ->expects(self::never())
            ->method('warning');
        $logger
            ->expects(self::never())
            ->method('error');
        $logger
            ->expects(self::never())
            ->method('critical');
        $logger
            ->expects(self::never())
            ->method('alert');
        $logger
            ->expects(self::never())
            ->method('emergency');

        $device = $this->createMock(DeviceInterface::class);
        $os     = $this->createMock(OsInterface::class);

        $deviceParser = $this->getMockBuilder(DeviceParserInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $deviceParser
            ->expects(self::once())
            ->method('parse')
            ->with($normalizedUseragent)
            ->willReturn([$device, $os]);

        $platformParser = $this->getMockBuilder(PlatformParserInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $platformParser
            ->expects(self::never())
            ->method('parse');

        $browser = $this->createMock(BrowserInterface::class);
        $engine  = $this->createMock(EngineInterface::class);

        $browserParser = $this->getMockBuilder(BrowserParserInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $browserParser
            ->expects(self::once())
            ->method('parse')
            ->with($normalizedUseragent)
            ->willReturn([$browser, $engine]);

        $engineParser = $this->getMockBuilder(EngineParserInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $engineParser
            ->expects(self::never())
            ->method('parse');
        $engineParser
            ->expects(self::never())
            ->method('load');

        $cache = $this->getMockBuilder(CacheInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $cache
            ->expects(self::once())
            ->method('hasItem')
            ->willReturn(false);
        $cache
            ->expects(self::never())
            ->method('getItem');
        $cache
            ->expects(self::once())
            ->method('setItem')
            ->willReturn(false);

        $normalizer = $this->getMockBuilder(NormalizerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $normalizer
            ->expects(self::exactly(3))
            ->method('normalize')
            ->with($useragent)
            ->willReturn($normalizedUseragent);

        /** @var \Psr\Log\LoggerInterface $logger */
        /** @var CacheInterface $cache */
        /** @var DeviceParserInterface $deviceParser */
        /** @var PlatformParserInterface $platformParser */
        /** @var BrowserParserInterface $browserParser */
        /** @var EngineParserInterface $engineParser */
        /** @var NormalizerInterface $normalizer */
        $object = new Detector(
            $logger,
            $cache,
            $deviceParser,
            $platformParser,
            $browserParser,
            $engineParser,
            $normalizer
        );

        /** @var Result $result */
        $result = $object->__invoke([Constants::HEADER_HTTP_USERAGENT => $useragent]);

        self::assertInstanceOf(ResultInterface::class, $result);
        self::assertSame($device, $result->getDevice());
        self::assertSame($os, $result->getOs());
        self::assertSame($browser, $result->getBrowser());
        self::assertSame($engine, $result->getEngine());
    }

    /**
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \PHPUnit\Framework\Exception
     * @throws \InvalidArgumentException
     * @throws \UnexpectedValueException
     *
     * @return void
     */
    public function testGetBrowserFromPsr7Message(): void
    {
        $useragent           = 'testagent';
        $normalizedUseragent = 'normalized testagent';
        $logger              = $this->getMockBuilder(LoggerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $logger
            ->expects(self::once())
            ->method('debug');
        $logger
            ->expects(self::never())
            ->method('info');
        $logger
            ->expects(self::never())
            ->method('notice');
        $logger
            ->expects(self::never())
            ->method('warning');
        $logger
            ->expects(self::never())
            ->method('error');
        $logger
            ->expects(self::never())
            ->method('critical');
        $logger
            ->expects(self::never())
            ->method('alert');
        $logger
            ->expects(self::never())
            ->method('emergency');

        $device = $this->getMockBuilder(DeviceInterface::class)->getMock();
        $device->expects(self::once())
            ->method('getDeviceName')
            ->willReturn('testDevice');
        $os = $this->createMock(OsInterface::class);

        $deviceParser = $this->getMockBuilder(DeviceParserInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $deviceParser
            ->expects(self::once())
            ->method('parse')
            ->with($normalizedUseragent)
            ->willReturn([$device, $os]);

        $platformParser = $this->getMockBuilder(PlatformParserInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $platformParser
            ->expects(self::never())
            ->method('parse');

        $browser = $this->createMock(BrowserInterface::class);
        $engine  = $this->createMock(EngineInterface::class);

        $browserParser = $this->getMockBuilder(BrowserParserInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $browserParser
            ->expects(self::once())
            ->method('parse')
            ->with($normalizedUseragent)
            ->willReturn([$browser, $engine]);

        $engineParser = $this->getMockBuilder(EngineParserInterface::class)
            ->disableOriginalConstructor()

            ->getMock();
        $engineParser
            ->expects(self::never())
            ->method('parse');
        $engineParser
            ->expects(self::never())
            ->method('load');

        $cache = $this->getMockBuilder(CacheInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $cache
            ->expects(self::once())
            ->method('hasItem')
            ->willReturn(false);
        $cache
            ->expects(self::never())
            ->method('getItem');
        $cache
            ->expects(self::once())
            ->method('setItem')
            ->willReturn(false);

        $normalizer = $this->getMockBuilder(NormalizerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $normalizer
            ->expects(self::exactly(3))
            ->method('normalize')
            ->with($useragent)
            ->willReturn($normalizedUseragent);

        /** @var \Psr\Log\LoggerInterface $logger */
        /** @var CacheInterface $cache */
        /** @var DeviceParserInterface $deviceParser */
        /** @var PlatformParserInterface $platformParser */
        /** @var BrowserParserInterface $browserParser */
        /** @var EngineParserInterface $engineParser */
        /** @var NormalizerInterface $normalizer */
        $object = new Detector(
            $logger,
            $cache,
            $deviceParser,
            $platformParser,
            $browserParser,
            $engineParser,
            $normalizer
        );

        $message = ServerRequestFactory::fromGlobals([Constants::HEADER_HTTP_USERAGENT => [$useragent]]);

        /** @var Result $result */
        $result = $object->__invoke($message);

        self::assertInstanceOf(ResultInterface::class, $result);
        self::assertSame($device, $result->getDevice());
        self::assertSame($os, $result->getOs());
        self::assertSame($browser, $result->getBrowser());
        self::assertSame($engine, $result->getEngine());

        self::assertInstanceOf(DeviceInterface::class, $result->getDevice());
        self::assertSame('testDevice', $result->getDevice()->getDeviceName());
    }

    /**
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \PHPUnit\Framework\Exception
     * @throws \InvalidArgumentException
     * @throws \UnexpectedValueException
     *
     * @return void
     */
    public function testGetBrowserFromUnknownDevice(): void
    {
        $useragent           = 'testagent';
        $normalizedUseragent = 'normalized testagent';
        $logger              = $this->getMockBuilder(LoggerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $logger
            ->expects(self::once())
            ->method('debug');
        $logger
            ->expects(self::never())
            ->method('info');
        $logger
            ->expects(self::never())
            ->method('notice');
        $logger
            ->expects(self::once())
            ->method('warning');
        $logger
            ->expects(self::never())
            ->method('error');
        $logger
            ->expects(self::never())
            ->method('critical');
        $logger
            ->expects(self::never())
            ->method('alert');
        $logger
            ->expects(self::never())
            ->method('emergency');

        $deviceParser = $this->getMockBuilder(DeviceParserInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $deviceParser
            ->expects(self::once())
            ->method('parse')
            ->with($normalizedUseragent)
            ->will(self::throwException(new NotFoundException('test')));

        $platformParser = $this->getMockBuilder(PlatformParserInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $platformParser
            ->expects(self::never())
            ->method('parse');

        $browser = $this->createMock(BrowserInterface::class);
        $engine  = $this->createMock(EngineInterface::class);

        $browserParser = $this->getMockBuilder(BrowserParserInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $browserParser
            ->expects(self::once())
            ->method('parse')
            ->with($normalizedUseragent)
            ->willReturn([$browser, $engine]);

        $engineParser = $this->getMockBuilder(EngineParserInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $engineParser
            ->expects(self::never())
            ->method('parse');
        $engineParser
            ->expects(self::never())
            ->method('load');

        $cache = $this->getMockBuilder(CacheInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $cache
            ->expects(self::once())
            ->method('hasItem')
            ->willReturn(false);
        $cache
            ->expects(self::never())
            ->method('getItem');
        $cache
            ->expects(self::once())
            ->method('setItem')
            ->willReturn(false);

        $normalizer = $this->getMockBuilder(NormalizerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $normalizer
            ->expects(self::exactly(3))
            ->method('normalize')
            ->with($useragent)
            ->willReturn($normalizedUseragent);

        /** @var \Psr\Log\LoggerInterface $logger */
        /** @var CacheInterface $cache */
        /** @var DeviceParserInterface $deviceParser */
        /** @var PlatformParserInterface $platformParser */
        /** @var BrowserParserInterface $browserParser */
        /** @var EngineParserInterface $engineParser */
        /** @var NormalizerInterface $normalizer */
        $object = new Detector(
            $logger,
            $cache,
            $deviceParser,
            $platformParser,
            $browserParser,
            $engineParser,
            $normalizer
        );

        $message = ServerRequestFactory::fromGlobals([Constants::HEADER_HTTP_USERAGENT => [$useragent]]);

        /** @var Result $result */
        $result = $object->__invoke($message);

        self::assertInstanceOf(ResultInterface::class, $result);
        self::assertSame($browser, $result->getBrowser());
        self::assertSame($engine, $result->getEngine());

        self::assertInstanceOf(DeviceInterface::class, $result->getDevice());
        self::assertNull($result->getDevice()->getDeviceName());
        self::assertNull($result->getOs()->getName());
    }

    /**
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \PHPUnit\Framework\Exception
     * @throws \InvalidArgumentException
     * @throws \UnexpectedValueException
     *
     * @return void
     */
    public function testGetBrowserFromUnknownDeviceAndPlatform(): void
    {
        $useragent           = 'testagent';
        $normalizedUseragent = 'normalized testagent';
        $logger              = $this->getMockBuilder(LoggerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $logger
            ->expects(self::once())
            ->method('debug');
        $logger
            ->expects(self::never())
            ->method('info');
        $logger
            ->expects(self::never())
            ->method('notice');
        $logger
            ->expects(self::once())
            ->method('warning');
        $logger
            ->expects(self::never())
            ->method('error');
        $logger
            ->expects(self::never())
            ->method('critical');
        $logger
            ->expects(self::never())
            ->method('alert');
        $logger
            ->expects(self::never())
            ->method('emergency');

        $deviceParser = $this->getMockBuilder(DeviceParserInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $deviceParser
            ->expects(self::once())
            ->method('parse')
            ->with($normalizedUseragent)
            ->will(self::throwException(new NotFoundException('test')));

        $platformParser = $this->getMockBuilder(PlatformParserInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $platformParser
            ->expects(self::never())
            ->method('parse');

        $browser = $this->createMock(BrowserInterface::class);
        $engine  = $this->createMock(EngineInterface::class);

        $browserParser = $this->getMockBuilder(BrowserParserInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $browserParser
            ->expects(self::once())
            ->method('parse')
            ->with($normalizedUseragent)
            ->willReturn([$browser, $engine]);

        $engineParser = $this->getMockBuilder(EngineParserInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $engineParser
            ->expects(self::never())
            ->method('parse');
        $engineParser
            ->expects(self::never())
            ->method('load');

        $cache = $this->getMockBuilder(CacheInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $cache
            ->expects(self::once())
            ->method('hasItem')
            ->willReturn(false);
        $cache
            ->expects(self::never())
            ->method('getItem');
        $cache
            ->expects(self::once())
            ->method('setItem')
            ->willReturn(false);

        $normalizer = $this->getMockBuilder(NormalizerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $normalizer
            ->expects(self::exactly(3))
            ->method('normalize')
            ->with($useragent)
            ->willReturn($normalizedUseragent);

        /** @var \Psr\Log\LoggerInterface $logger */
        /** @var CacheInterface $cache */
        /** @var DeviceParserInterface $deviceParser */
        /** @var PlatformParserInterface $platformParser */
        /** @var BrowserParserInterface $browserParser */
        /** @var EngineParserInterface $engineParser */
        /** @var NormalizerInterface $normalizer */
        $object = new Detector(
            $logger,
            $cache,
            $deviceParser,
            $platformParser,
            $browserParser,
            $engineParser,
            $normalizer
        );

        $message = ServerRequestFactory::fromGlobals([Constants::HEADER_HTTP_USERAGENT => [$useragent]]);

        /** @var Result $result */
        $result = $object->__invoke($message);

        self::assertInstanceOf(ResultInterface::class, $result);
        //self::assertSame($device, $result->getDevice());
        //self::assertSame($os, $result->getOs());
        self::assertSame($browser, $result->getBrowser());
        self::assertSame($engine, $result->getEngine());

        self::assertInstanceOf(DeviceInterface::class, $result->getDevice());
        self::assertNull($result->getDevice()->getDeviceName());
        self::assertNull($result->getOs()->getName());
    }

    /**
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \PHPUnit\Framework\Exception
     * @throws \InvalidArgumentException
     * @throws \UnexpectedValueException
     *
     * @return void
     */
    public function testGetBrowserWithoutEngine(): void
    {
        $useragent           = 'testagent';
        $normalizedUseragent = 'normalized testagent';
        $logger              = $this->getMockBuilder(LoggerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $logger
            ->expects(self::exactly(2))
            ->method('debug');
        $logger
            ->expects(self::never())
            ->method('info');
        $logger
            ->expects(self::never())
            ->method('notice');
        $logger
            ->expects(self::never())
            ->method('warning');
        $logger
            ->expects(self::never())
            ->method('error');
        $logger
            ->expects(self::never())
            ->method('critical');
        $logger
            ->expects(self::never())
            ->method('alert');
        $logger
            ->expects(self::never())
            ->method('emergency');

        $device = $this->getMockBuilder(DeviceInterface::class)->getMock();
        $device->expects(self::once())
            ->method('getDeviceName')
            ->willReturn('testDevice');
        $os = $this->createMock(OsInterface::class);

        $deviceParser = $this->getMockBuilder(DeviceParserInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $deviceParser
            ->expects(self::once())
            ->method('parse')
            ->with($normalizedUseragent)
            ->willReturn([$device, $os]);

        $platformParser = $this->getMockBuilder(PlatformParserInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $platformParser
            ->expects(self::never())
            ->method('parse');

        $browser = $this->createMock(BrowserInterface::class);
        $engine  = $this->getMockBuilder(EngineInterface::class)->getMock();
        $engine->expects(self::once())
            ->method('getName')
            ->willReturn('test-engine');

        $browserParser = $this->getMockBuilder(BrowserParserInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $browserParser
            ->expects(self::once())
            ->method('parse')
            ->with($normalizedUseragent)
            ->willReturn([$browser, null]);

        $engineParser = $this->getMockBuilder(EngineParserInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $engineParser
            ->expects(self::once())
            ->method('parse')
            ->with($normalizedUseragent)
            ->willReturn($engine);
        $engineParser
            ->expects(self::never())
            ->method('load');

        $cache = $this->getMockBuilder(CacheInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $cache
            ->expects(self::once())
            ->method('hasItem')
            ->willReturn(false);
        $cache
            ->expects(self::never())
            ->method('getItem');
        $cache
            ->expects(self::once())
            ->method('setItem')
            ->willReturn(false);

        $normalizer = $this->getMockBuilder(NormalizerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $normalizer
            ->expects(self::exactly(3))
            ->method('normalize')
            ->with($useragent)
            ->willReturn($normalizedUseragent);

        /** @var \Psr\Log\LoggerInterface $logger */
        /** @var CacheInterface $cache */
        /** @var DeviceParserInterface $deviceParser */
        /** @var PlatformParserInterface $platformParser */
        /** @var BrowserParserInterface $browserParser */
        /** @var EngineParserInterface $engineParser */
        /** @var NormalizerInterface $normalizer */
        $object = new Detector(
            $logger,
            $cache,
            $deviceParser,
            $platformParser,
            $browserParser,
            $engineParser,
            $normalizer
        );

        $message = ServerRequestFactory::fromGlobals([Constants::HEADER_HTTP_USERAGENT => [$useragent]]);

        /** @var Result $result */
        $result = $object->__invoke($message);

        self::assertInstanceOf(ResultInterface::class, $result);
        self::assertSame($device, $result->getDevice());
        self::assertSame($os, $result->getOs());
        self::assertSame($browser, $result->getBrowser());
        self::assertSame($engine, $result->getEngine());

        self::assertInstanceOf(DeviceInterface::class, $result->getDevice());
        self::assertSame('testDevice', $result->getDevice()->getDeviceName());
        self::assertSame('test-engine', $result->getEngine()->getName());
    }

    /**
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \PHPUnit\Framework\Exception
     * @throws \InvalidArgumentException
     * @throws \UnexpectedValueException
     *
     * @return void
     */
    public function testGetBrowserWithoutEngine2(): void
    {
        $useragent           = 'testagent';
        $normalizedUseragent = 'normalized testagent';
        $logger              = $this->getMockBuilder(LoggerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $logger
            ->expects(self::exactly(2))
            ->method('debug');
        $logger
            ->expects(self::never())
            ->method('info');
        $logger
            ->expects(self::never())
            ->method('notice');
        $logger
            ->expects(self::never())
            ->method('warning');
        $logger
            ->expects(self::once())
            ->method('error');
        $logger
            ->expects(self::never())
            ->method('critical');
        $logger
            ->expects(self::never())
            ->method('alert');
        $logger
            ->expects(self::never())
            ->method('emergency');

        $device = $this->getMockBuilder(DeviceInterface::class)->getMock();
        $device->expects(self::once())
            ->method('getDeviceName')
            ->willReturn('testDevice');
        $os = $this->createMock(OsInterface::class);

        $deviceParser = $this->getMockBuilder(DeviceParserInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $deviceParser
            ->expects(self::once())
            ->method('parse')
            ->with($normalizedUseragent)
            ->willReturn([$device, $os]);

        $platformParser = $this->getMockBuilder(PlatformParserInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $platformParser
            ->expects(self::never())
            ->method('parse');

        $browser = $this->createMock(BrowserInterface::class);

        $browserParser = $this->getMockBuilder(BrowserParserInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $browserParser
            ->expects(self::once())
            ->method('parse')
            ->with($normalizedUseragent)
            ->willReturn([$browser, null]);

        $engineParser = $this->getMockBuilder(EngineParserInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $engineParser
            ->expects(self::once())
            ->method('parse')
            ->with($normalizedUseragent)
            ->will(self::throwException(new NotFoundException('test')));
        $engineParser
            ->expects(self::never())
            ->method('load');

        $cache = $this->getMockBuilder(CacheInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $cache
            ->expects(self::once())
            ->method('hasItem')
            ->willReturn(false);
        $cache
            ->expects(self::never())
            ->method('getItem');
        $cache
            ->expects(self::once())
            ->method('setItem')
            ->willReturn(false);

        $normalizer = $this->getMockBuilder(NormalizerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $normalizer
            ->expects(self::exactly(3))
            ->method('normalize')
            ->with($useragent)
            ->willReturn($normalizedUseragent);

        /** @var \Psr\Log\LoggerInterface $logger */
        /** @var CacheInterface $cache */
        /** @var DeviceParserInterface $deviceParser */
        /** @var PlatformParserInterface $platformParser */
        /** @var BrowserParserInterface $browserParser */
        /** @var EngineParserInterface $engineParser */
        /** @var NormalizerInterface $normalizer */
        $object = new Detector(
            $logger,
            $cache,
            $deviceParser,
            $platformParser,
            $browserParser,
            $engineParser,
            $normalizer
        );

        $message = ServerRequestFactory::fromGlobals([Constants::HEADER_HTTP_USERAGENT => [$useragent]]);

        /** @var Result $result */
        $result = $object->__invoke($message);

        self::assertInstanceOf(ResultInterface::class, $result);
        self::assertSame($device, $result->getDevice());
        self::assertSame($os, $result->getOs());
        self::assertSame($browser, $result->getBrowser());
        //self::assertSame($engine, $result->getEngine());

        self::assertInstanceOf(DeviceInterface::class, $result->getDevice());
        self::assertSame('testDevice', $result->getDevice()->getDeviceName());
        self::assertNull($result->getEngine()->getName());
    }

    /**
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \PHPUnit\Framework\Exception
     * @throws \InvalidArgumentException
     * @throws \UnexpectedValueException
     *
     * @return void
     */
    public function testGetBrowserWithoutEngineIos(): void
    {
        $useragent           = 'testagent';
        $normalizedUseragent = 'normalized testagent';
        $logger              = $this->getMockBuilder(LoggerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $logger
            ->expects(self::once())
            ->method('debug');
        $logger
            ->expects(self::never())
            ->method('info');
        $logger
            ->expects(self::never())
            ->method('notice');
        $logger
            ->expects(self::never())
            ->method('warning');
        $logger
            ->expects(self::never())
            ->method('error');
        $logger
            ->expects(self::never())
            ->method('critical');
        $logger
            ->expects(self::never())
            ->method('alert');
        $logger
            ->expects(self::never())
            ->method('emergency');

        $device = $this->getMockBuilder(DeviceInterface::class)->getMock();
        $device->expects(self::once())
            ->method('getDeviceName')
            ->willReturn('testDevice');

        $os = $this->getMockBuilder(OsInterface::class)->getMock();
        $os->expects(self::exactly(2))
            ->method('getName')
            ->willReturn('iOS');

        $deviceParser = $this->getMockBuilder(DeviceParserInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $deviceParser
            ->expects(self::once())
            ->method('parse')
            ->with($normalizedUseragent)
            ->willReturn([$device, $os]);

        $platformParser = $this->getMockBuilder(PlatformParserInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $platformParser
            ->expects(self::never())
            ->method('parse');

        $browser = $this->createMock(BrowserInterface::class);

        $browserParser = $this->getMockBuilder(BrowserParserInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $browserParser
            ->expects(self::once())
            ->method('parse')
            ->with($normalizedUseragent)
            ->willReturn([$browser, null]);

        $engineParser = $this->getMockBuilder(EngineParserInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $engineParser
            ->expects(self::never())
            ->method('parse');

        $engine = $this->getMockBuilder(EngineInterface::class)->getMock();
        $engine->expects(self::once())
            ->method('getName')
            ->willReturn('webkit-test');
        $engineParser
            ->expects(self::once())
            ->method('load')
            ->with('webkit', $normalizedUseragent)
            ->willReturn($engine);

        $cache = $this->getMockBuilder(CacheInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $cache
            ->expects(self::once())
            ->method('hasItem')
            ->willReturn(false);
        $cache
            ->expects(self::never())
            ->method('getItem');
        $cache
            ->expects(self::once())
            ->method('setItem')
            ->willReturn(false);

        $normalizer = $this->getMockBuilder(NormalizerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $normalizer
            ->expects(self::exactly(3))
            ->method('normalize')
            ->with($useragent)
            ->willReturn($normalizedUseragent);

        /** @var \Psr\Log\LoggerInterface $logger */
        /** @var CacheInterface $cache */
        /** @var DeviceParserInterface $deviceParser */
        /** @var PlatformParserInterface $platformParser */
        /** @var BrowserParserInterface $browserParser */
        /** @var EngineParserInterface $engineParser */
        /** @var NormalizerInterface $normalizer */
        $object = new Detector(
            $logger,
            $cache,
            $deviceParser,
            $platformParser,
            $browserParser,
            $engineParser,
            $normalizer
        );

        $message = ServerRequestFactory::fromGlobals([Constants::HEADER_HTTP_USERAGENT => [$useragent]]);

        /** @var Result $result */
        $result = $object->__invoke($message);

        self::assertInstanceOf(ResultInterface::class, $result);
        self::assertSame($device, $result->getDevice());
        self::assertSame($os, $result->getOs());
        self::assertSame($browser, $result->getBrowser());
        self::assertSame($engine, $result->getEngine());

        self::assertInstanceOf(DeviceInterface::class, $result->getDevice());
        self::assertSame('testDevice', $result->getDevice()->getDeviceName());
        self::assertSame('webkit-test', $result->getEngine()->getName());
        self::assertSame('iOS', $result->getOs()->getName());
    }

    /**
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \PHPUnit\Framework\Exception
     * @throws \InvalidArgumentException
     * @throws \UnexpectedValueException
     *
     * @return void
     */
    public function testGetBrowserWithoutEngineIosFail(): void
    {
        $useragent           = 'testagent';
        $normalizedUseragent = 'normalized testagent';
        $logger              = $this->getMockBuilder(LoggerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $logger
            ->expects(self::once())
            ->method('debug');
        $logger
            ->expects(self::never())
            ->method('info');
        $logger
            ->expects(self::never())
            ->method('notice');
        $logger
            ->expects(self::once())
            ->method('warning');
        $logger
            ->expects(self::never())
            ->method('error');
        $logger
            ->expects(self::never())
            ->method('critical');
        $logger
            ->expects(self::never())
            ->method('alert');
        $logger
            ->expects(self::never())
            ->method('emergency');

        $device = $this->getMockBuilder(DeviceInterface::class)->getMock();
        $device->expects(self::once())
            ->method('getDeviceName')
            ->willReturn('testDevice');
        $os = $this->getMockBuilder(OsInterface::class)->getMock();
        $os->expects(self::exactly(2))
            ->method('getName')
            ->willReturn('iOS');

        $deviceParser = $this->getMockBuilder(DeviceParserInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $deviceParser
            ->expects(self::once())
            ->method('parse')
            ->with($normalizedUseragent)
            ->willReturn([$device, $os]);

        $platformParser = $this->getMockBuilder(PlatformParserInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $platformParser
            ->expects(self::never())
            ->method('parse');

        $browser = $this->createMock(BrowserInterface::class);

        $browserParser = $this->getMockBuilder(BrowserParserInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $browserParser
            ->expects(self::once())
            ->method('parse')
            ->with($normalizedUseragent)
            ->willReturn([$browser, null]);

        $engineParser = $this->getMockBuilder(EngineParserInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $engineParser
            ->expects(self::never())
            ->method('parse');
        $engineParser
            ->expects(self::once())
            ->method('load')
            ->with('webkit', $normalizedUseragent)
            ->will(self::throwException(new \UnexpectedValueException('parsing failed')));

        $cache = $this->getMockBuilder(CacheInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $cache
            ->expects(self::once())
            ->method('hasItem')
            ->willReturn(false);
        $cache
            ->expects(self::never())
            ->method('getItem');
        $cache
            ->expects(self::once())
            ->method('setItem')
            ->willReturn(false);

        $normalizer = $this->getMockBuilder(NormalizerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $normalizer
            ->expects(self::exactly(3))
            ->method('normalize')
            ->with($useragent)
            ->willReturn($normalizedUseragent);

        /** @var \Psr\Log\LoggerInterface $logger */
        /** @var CacheInterface $cache */
        /** @var DeviceParserInterface $deviceParser */
        /** @var PlatformParserInterface $platformParser */
        /** @var BrowserParserInterface $browserParser */
        /** @var EngineParserInterface $engineParser */
        /** @var NormalizerInterface $normalizer */
        $object = new Detector(
            $logger,
            $cache,
            $deviceParser,
            $platformParser,
            $browserParser,
            $engineParser,
            $normalizer
        );

        $message = ServerRequestFactory::fromGlobals([Constants::HEADER_HTTP_USERAGENT => [$useragent]]);

        /** @var Result $result */
        $result = $object->__invoke($message);

        self::assertInstanceOf(ResultInterface::class, $result);
        self::assertSame($device, $result->getDevice());
        self::assertSame($os, $result->getOs());
        self::assertSame($browser, $result->getBrowser());
        //self::assertSame($engine, $result->getEngine());

        self::assertInstanceOf(DeviceInterface::class, $result->getDevice());
        self::assertSame('testDevice', $result->getDevice()->getDeviceName());
        self::assertNull($result->getEngine()->getName());
        self::assertSame('iOS', $result->getOs()->getName());
    }

    /**
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \PHPUnit\Framework\Exception
     * @throws \InvalidArgumentException
     * @throws \UnexpectedValueException
     *
     * @return void
     */
    public function testGetBrowserWithoutEngineIosFail2(): void
    {
        $useragent           = 'testagent';
        $normalizedUseragent = 'normalized testagent';
        $logger              = $this->getMockBuilder(LoggerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $logger
            ->expects(self::once())
            ->method('debug');
        $logger
            ->expects(self::never())
            ->method('info');
        $logger
            ->expects(self::never())
            ->method('notice');
        $logger
            ->expects(self::once())
            ->method('warning');
        $logger
            ->expects(self::never())
            ->method('error');
        $logger
            ->expects(self::never())
            ->method('critical');
        $logger
            ->expects(self::never())
            ->method('alert');
        $logger
            ->expects(self::never())
            ->method('emergency');

        $device = $this->getMockBuilder(DeviceInterface::class)->getMock();
        $device->expects(self::once())
            ->method('getDeviceName')
            ->willReturn('testDevice');
        $os = $this->getMockBuilder(OsInterface::class)->getMock();
        $os->expects(self::exactly(2))
            ->method('getName')
            ->willReturn('iOS');

        $deviceParser = $this->getMockBuilder(DeviceParserInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $deviceParser
            ->expects(self::once())
            ->method('parse')
            ->with($normalizedUseragent)
            ->willReturn([$device, $os]);

        $platformParser = $this->getMockBuilder(PlatformParserInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $platformParser
            ->expects(self::never())
            ->method('parse');

        $browser = $this->createMock(BrowserInterface::class);

        $browserParser = $this->getMockBuilder(BrowserParserInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $browserParser
            ->expects(self::once())
            ->method('parse')
            ->with($normalizedUseragent)
            ->willReturn([$browser, null]);

        $engineParser = $this->getMockBuilder(EngineParserInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $engineParser
            ->expects(self::never())
            ->method('parse');
        $engineParser
            ->expects(self::once())
            ->method('load')
            ->with('webkit', $normalizedUseragent)
            ->will(self::throwException(new NotFoundException('something not found')));

        $cache = $this->getMockBuilder(CacheInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $cache
            ->expects(self::once())
            ->method('hasItem')
            ->willReturn(false);
        $cache
            ->expects(self::never())
            ->method('getItem');
        $cache
            ->expects(self::once())
            ->method('setItem')
            ->willReturn(false);

        $normalizer = $this->getMockBuilder(NormalizerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $normalizer
            ->expects(self::exactly(3))
            ->method('normalize')
            ->with($useragent)
            ->willReturn($normalizedUseragent);

        /** @var \Psr\Log\LoggerInterface $logger */
        /** @var CacheInterface $cache */
        /** @var DeviceParserInterface $deviceParser */
        /** @var PlatformParserInterface $platformParser */
        /** @var BrowserParserInterface $browserParser */
        /** @var EngineParserInterface $engineParser */
        /** @var NormalizerInterface $normalizer */
        $object = new Detector(
            $logger,
            $cache,
            $deviceParser,
            $platformParser,
            $browserParser,
            $engineParser,
            $normalizer
        );

        $message = ServerRequestFactory::fromGlobals([Constants::HEADER_HTTP_USERAGENT => [$useragent]]);

        /** @var Result $result */
        $result = $object->__invoke($message);

        self::assertInstanceOf(ResultInterface::class, $result);
        self::assertSame($device, $result->getDevice());
        self::assertSame($os, $result->getOs());
        self::assertSame($browser, $result->getBrowser());
        //self::assertSame($engine, $result->getEngine());

        self::assertInstanceOf(DeviceInterface::class, $result->getDevice());
        self::assertSame('testDevice', $result->getDevice()->getDeviceName());
        self::assertNull($result->getEngine()->getName());
        self::assertSame('iOS', $result->getOs()->getName());
    }

    /**
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \PHPUnit\Framework\Exception
     * @throws \InvalidArgumentException
     * @throws \UnexpectedValueException
     *
     * @return void
     */
    public function testGetBrowserWithBrowserFactoryFail(): void
    {
        $useragent           = 'testagent';
        $normalizedUseragent = 'normalized testagent';
        $logger              = $this->getMockBuilder(LoggerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $logger
            ->expects(self::once())
            ->method('debug');
        $logger
            ->expects(self::never())
            ->method('info');
        $logger
            ->expects(self::never())
            ->method('notice');
        $logger
            ->expects(self::never())
            ->method('warning');
        $logger
            ->expects(self::once())
            ->method('error');
        $logger
            ->expects(self::never())
            ->method('critical');
        $logger
            ->expects(self::never())
            ->method('alert');
        $logger
            ->expects(self::never())
            ->method('emergency');

        $device = $this->getMockBuilder(DeviceInterface::class)->getMock();
        $device->expects(self::never())
            ->method('getDeviceName')
            ->willReturn('testDevice');
        $os = $this->getMockBuilder(OsInterface::class)->getMock();
        $os->expects(self::once())
            ->method('getName')
            ->willReturn('iOS');

        $deviceParser = $this->getMockBuilder(DeviceParserInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $deviceParser
            ->expects(self::once())
            ->method('parse')
            ->with($normalizedUseragent)
            ->willReturn([$device, $os]);

        $platformParser = $this->getMockBuilder(PlatformParserInterface::class)
            ->disableOriginalConstructor()

            ->getMock();
        $platformParser
            ->expects(self::never())
            ->method('parse');

        $browserParser = $this->getMockBuilder(BrowserParserInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $browserParser
            ->expects(self::once())
            ->method('parse')
            ->with($normalizedUseragent)
            ->willThrowException(new NotFoundException('parsing failed'));

        $engineParser = $this->getMockBuilder(EngineParserInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $engineParser
            ->expects(self::never())
            ->method('parse');

        $engine2 = $this->getMockBuilder(EngineInterface::class)->getMock();
        $engine2->expects(self::never())
            ->method('getName');

        $engineParser
            ->expects(self::once())
            ->method('load')
            ->with('webkit', $normalizedUseragent)
            ->willReturn($engine2);

        $cache = $this->getMockBuilder(CacheInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $cache
            ->expects(self::once())
            ->method('hasItem')
            ->willReturn(false);
        $cache
            ->expects(self::never())
            ->method('getItem');
        $cache
            ->expects(self::once())
            ->method('setItem')
            ->willReturn(false);

        $normalizer = $this->getMockBuilder(NormalizerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $normalizer
            ->expects(self::exactly(3))
            ->method('normalize')
            ->with($useragent)
            ->willReturn($normalizedUseragent);

        /** @var \Psr\Log\LoggerInterface $logger */
        /** @var CacheInterface $cache */
        /** @var DeviceParserInterface $deviceParser */
        /** @var PlatformParserInterface $platformParser */
        /** @var BrowserParserInterface $browserParser */
        /** @var EngineParserInterface $engineParser */
        /** @var NormalizerInterface $normalizer */
        $object = new Detector(
            $logger,
            $cache,
            $deviceParser,
            $platformParser,
            $browserParser,
            $engineParser,
            $normalizer
        );

        $message = ServerRequestFactory::fromGlobals([Constants::HEADER_HTTP_USERAGENT => [$useragent]]);

        /** @var Result $result */
        $result = $object->__invoke($message);

        self::assertInstanceOf(ResultInterface::class, $result);
        self::assertSame($device, $result->getDevice());
        self::assertSame($os, $result->getOs());
        //self::assertSame($browser, $result->getBrowser());
        //self::assertSame($engine, $result->getEngine());

        self::assertInstanceOf(DeviceInterface::class, $result->getDevice());
        self::assertInstanceOf(BrowserInterface::class, $result->getBrowser());
        self::assertNull($result->getBrowser()->getName());
        self::assertNull($result->getBrowser()->getBits());
    }

    /**
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \PHPUnit\Framework\Exception
     * @throws \InvalidArgumentException
     * @throws \UnexpectedValueException
     *
     * @return void
     */
    public function testGetDeviceWithoutPlatform(): void
    {
        $useragent           = 'testagent';
        $normalizedUseragent = 'normalized testagent';
        $logger              = $this->getMockBuilder(LoggerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $logger
            ->expects(self::exactly(3))
            ->method('debug');
        $logger
            ->expects(self::never())
            ->method('info');
        $logger
            ->expects(self::never())
            ->method('notice');
        $logger
            ->expects(self::never())
            ->method('warning');
        $logger
            ->expects(self::never())
            ->method('error');
        $logger
            ->expects(self::never())
            ->method('critical');
        $logger
            ->expects(self::never())
            ->method('alert');
        $logger
            ->expects(self::never())
            ->method('emergency');

        $device = $this->getMockBuilder(DeviceInterface::class)->getMock();
        $device->expects(self::once())
            ->method('getDeviceName')
            ->willReturn('testDevice');
        $os = $this->createMock(OsInterface::class);

        $deviceParser = $this->getMockBuilder(DeviceParserInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $deviceParser
            ->expects(self::once())
            ->method('parse')
            ->with($normalizedUseragent)
            ->willReturn([$device, null]);

        $platformParser = $this->getMockBuilder(PlatformParserInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $platformParser
            ->expects(self::once())
            ->method('parse')
            ->with($normalizedUseragent)
            ->willReturn($os);

        $browser = $this->createMock(BrowserInterface::class);
        $engine  = $this->getMockBuilder(EngineInterface::class)->getMock();
        $engine->expects(self::once())
            ->method('getName')
            ->willReturn('test-engine');

        $browserParser = $this->getMockBuilder(BrowserParserInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $browserParser
            ->expects(self::once())
            ->method('parse')
            ->with($normalizedUseragent)
            ->willReturn([$browser, null]);

        $engineParser = $this->getMockBuilder(EngineParserInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $engineParser
            ->expects(self::once())
            ->method('parse')
            ->with($normalizedUseragent)
            ->willReturn($engine);
        $engineParser
            ->expects(self::never())
            ->method('load');

        $cache = $this->getMockBuilder(CacheInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $cache
            ->expects(self::once())
            ->method('hasItem')
            ->willReturn(false);
        $cache
            ->expects(self::never())
            ->method('getItem');
        $cache
            ->expects(self::once())
            ->method('setItem')
            ->willReturn(false);

        $normalizer = $this->getMockBuilder(NormalizerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $normalizer
            ->expects(self::exactly(4))
            ->method('normalize')
            ->with($useragent)
            ->willReturn($normalizedUseragent);

        /** @var \Psr\Log\LoggerInterface $logger */
        /** @var CacheInterface $cache */
        /** @var DeviceParserInterface $deviceParser */
        /** @var PlatformParserInterface $platformParser */
        /** @var BrowserParserInterface $browserParser */
        /** @var EngineParserInterface $engineParser */
        /** @var NormalizerInterface $normalizer */
        $object = new Detector(
            $logger,
            $cache,
            $deviceParser,
            $platformParser,
            $browserParser,
            $engineParser,
            $normalizer
        );

        $message = ServerRequestFactory::fromGlobals([Constants::HEADER_HTTP_USERAGENT => [$useragent]]);

        /** @var Result $result */
        $result = $object->__invoke($message);

        self::assertInstanceOf(ResultInterface::class, $result);
        self::assertSame($device, $result->getDevice());
        self::assertSame($os, $result->getOs());
        self::assertSame($browser, $result->getBrowser());
        self::assertSame($engine, $result->getEngine());

        self::assertInstanceOf(DeviceInterface::class, $result->getDevice());
        self::assertSame('testDevice', $result->getDevice()->getDeviceName());
        self::assertSame('test-engine', $result->getEngine()->getName());
    }

    /**
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \PHPUnit\Framework\Exception
     * @throws \InvalidArgumentException
     * @throws \UnexpectedValueException
     *
     * @return void
     */
    public function testGetDeviceWithoutPlatformAndError(): void
    {
        $useragent           = 'testagent';
        $normalizedUseragent = 'normalized testagent';
        $logger              = $this->getMockBuilder(LoggerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $logger
            ->expects(self::exactly(3))
            ->method('debug');
        $logger
            ->expects(self::never())
            ->method('info');
        $logger
            ->expects(self::never())
            ->method('notice');
        $logger
            ->expects(self::once())
            ->method('warning');
        $logger
            ->expects(self::never())
            ->method('error');
        $logger
            ->expects(self::never())
            ->method('critical');
        $logger
            ->expects(self::never())
            ->method('alert');
        $logger
            ->expects(self::never())
            ->method('emergency');

        $device = $this->getMockBuilder(DeviceInterface::class)->getMock();
        $device->expects(self::once())
            ->method('getDeviceName')
            ->willReturn('testDevice');

        $deviceParser = $this->getMockBuilder(DeviceParserInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $deviceParser
            ->expects(self::once())
            ->method('parse')
            ->with($normalizedUseragent)
            ->willReturn([$device, null]);

        $platformParser = $this->getMockBuilder(PlatformParserInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $platformParser
            ->expects(self::once())
            ->method('parse')
            ->with($normalizedUseragent)
            ->willThrowException(new NotFoundException('platform not found'));

        $browser = $this->createMock(BrowserInterface::class);
        $engine  = $this->getMockBuilder(EngineInterface::class)->getMock();
        $engine->expects(self::once())
            ->method('getName')
            ->willReturn('test-engine');

        $browserParser = $this->getMockBuilder(BrowserParserInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $browserParser
            ->expects(self::once())
            ->method('parse')
            ->with($normalizedUseragent)
            ->willReturn([$browser, null]);

        $engineParser = $this->getMockBuilder(EngineParserInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $engineParser
            ->expects(self::once())
            ->method('parse')
            ->with($normalizedUseragent)
            ->willReturn($engine);
        $engineParser
            ->expects(self::never())
            ->method('load');

        $cache = $this->getMockBuilder(CacheInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $cache
            ->expects(self::once())
            ->method('hasItem')
            ->willReturn(false);
        $cache
            ->expects(self::never())
            ->method('getItem');
        $cache
            ->expects(self::once())
            ->method('setItem')
            ->willReturn(false);

        $normalizer = $this->getMockBuilder(NormalizerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $normalizer
            ->expects(self::exactly(4))
            ->method('normalize')
            ->with($useragent)
            ->willReturn($normalizedUseragent);

        /** @var \Psr\Log\LoggerInterface $logger */
        /** @var CacheInterface $cache */
        /** @var DeviceParserInterface $deviceParser */
        /** @var PlatformParserInterface $platformParser */
        /** @var BrowserParserInterface $browserParser */
        /** @var EngineParserInterface $engineParser */
        /** @var NormalizerInterface $normalizer */
        $object = new Detector(
            $logger,
            $cache,
            $deviceParser,
            $platformParser,
            $browserParser,
            $engineParser,
            $normalizer
        );

        $message = ServerRequestFactory::fromGlobals([Constants::HEADER_HTTP_USERAGENT => [$useragent]]);

        /** @var Result $result */
        $result = $object->__invoke($message);

        self::assertInstanceOf(ResultInterface::class, $result);
        self::assertSame($device, $result->getDevice());
        //self::assertSame($os, $result->getOs());
        self::assertSame($browser, $result->getBrowser());
        self::assertSame($engine, $result->getEngine());

        self::assertInstanceOf(DeviceInterface::class, $result->getDevice());
        self::assertSame('testDevice', $result->getDevice()->getDeviceName());
        self::assertSame('test-engine', $result->getEngine()->getName());

        self::assertNull($result->getOs()->getName());
        self::assertNull($result->getOs()->getMarketingName());
        self::assertNull($result->getOs()->getBits());
    }
}
