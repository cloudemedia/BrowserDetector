<?php
/**
 * This file is part of the browser-detector package.
 *
 * Copyright (c) 2012-2017, Thomas Mueller <mimmi20@live.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types = 1);
namespace BrowserDetector\Version;

use BrowserDetector\Helper\MicrosoftOffice as MicrosoftOfficeHelper;

/**
 * @author Thomas Müller <mimmi20@live.de>
 */
class MicrosoftOffice implements VersionCacheFactoryInterface
{
    /**
     * returns the version of the operating system/platform
     *
     * @param string $useragent
     *
     * @return \BrowserDetector\Version\VersionInterface
     */
    public function detectVersion(string $useragent): VersionInterface
    {
        $helper = new MicrosoftOfficeHelper();

        return VersionFactory::set($helper->mapVersion($helper->detectInternalVersion($useragent)));
    }
}
