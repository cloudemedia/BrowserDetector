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
namespace BrowserDetectorTest\Version;

use BrowserDetector\Version\CoreMedia;
use BrowserDetector\Version\CoreMediaFactory;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;

final class CoreMediaFactoryTest extends TestCase
{
    /**
     * @var \BrowserDetector\Version\CoreMediaFactory
     */
    private $object;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $this->object = new CoreMediaFactory();
    }

    /**
     * @return void
     */
    public function testInvoke(): void
    {
        /** @var CoreMediaFactory $object */
        $object = $this->object;
        $result = $object(new NullLogger());
        self::assertInstanceOf(CoreMedia::class, $result);
    }
}
