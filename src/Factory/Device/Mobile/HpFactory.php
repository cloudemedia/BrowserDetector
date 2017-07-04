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
namespace BrowserDetector\Factory\Device\Mobile;

use BrowserDetector\Factory;
use BrowserDetector\Loader\LoaderInterface;
use Stringy\Stringy;

/**
 * @category  BrowserDetector
 *
 * @copyright 2012-2017 Thomas Mueller
 * @license   http://www.opensource.org/licenses/MIT MIT License
 */
class HpFactory implements Factory\FactoryInterface
{
    /**
     * @var array
     */
    private $devices = [
        'slatebook 10 x2 pc' => 'hp slatebook 10 x2 pc',
        '7 plus'             => 'hp slate 7 plus',
        'ipaqhw6900'         => 'ipaq 6900',
        'slate 17'           => 'slate 17',
        'slate 10 hd'        => 'slate 10',
        'touchpad'           => 'touchpad',
        'cm_tenderloin'      => 'touchpad',
        'palm-d050'          => 'tx',
        'pre/'               => 'pre',
        'pixi/'              => 'pixi',
        'blazer'             => 'blazer',
        'p160u'              => 'p160u',
    ];

    /**
     * @var \BrowserDetector\Loader\LoaderInterface|null
     */
    private $loader = null;

    /**
     * @param \BrowserDetector\Loader\LoaderInterface $loader
     */
    public function __construct(LoaderInterface $loader)
    {
        $this->loader = $loader;
    }

    /**
     * detects the device name from the given user agent
     *
     * @param string           $useragent
     * @param \Stringy\Stringy $s
     *
     * @return array
     */
    public function detect($useragent, Stringy $s = null)
    {
        foreach ($this->devices as $search => $key) {
            if ($s->contains($search, false)) {
                return $this->loader->load($key, $useragent);
            }
        }

        return $this->loader->load('general hp device', $useragent);
    }
}
