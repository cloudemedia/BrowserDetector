<?php
/**
 * This file is part of the browser-detector package.
 *
 * Copyright (c) 2012-2018, Thomas Mueller <mimmi20@live.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types = 1);
namespace BrowserDetector\Loader;

use BrowserDetector\Loader\Helper\Data;
use BrowserDetector\Parser\PlatformParserInterface;
use JsonClass\JsonInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Finder\Finder;

final class DeviceLoaderFactory implements SpecificLoaderFactoryInterface
{
    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    /**
     * @var \JsonClass\JsonInterface
     */
    private $jsonParser;

    /**
     * @var \BrowserDetector\Parser\PlatformParserInterface
     */
    private $platformParser;

    /**
     * @param \Psr\Log\LoggerInterface                        $logger
     * @param \JsonClass\JsonInterface                        $jsonParser
     * @param \BrowserDetector\Parser\PlatformParserInterface $platformParser
     */
    public function __construct(LoggerInterface $logger, JsonInterface $jsonParser, PlatformParserInterface $platformParser)
    {
        $this->logger         = $logger;
        $this->jsonParser     = $jsonParser;
        $this->platformParser = $platformParser;
    }

    /**
     * @param string $company
     *
     * @return SpecificLoaderInterface
     */
    public function __invoke(string $company = ''): SpecificLoaderInterface
    {
        /** @var DeviceLoader[] $loader */
        static $loader = [];

        if (array_key_exists($company, $loader)) {
            return $loader[$company];
        }

        $dataPath = __DIR__ . '/../../data/devices/' . $company;

        $finder = new Finder();
        $finder->files();
        $finder->name('*.json');
        $finder->ignoreDotFiles(true);
        $finder->ignoreVCS(true);
        $finder->ignoreUnreadableDirs();
        $finder->in($dataPath);

        $initData = new Data($finder, $this->jsonParser);

        $loader[$company] = new DeviceLoader(
            $this->logger,
            $this->platformParser,
            $initData
        );

        return $loader[$company];
    }
}
