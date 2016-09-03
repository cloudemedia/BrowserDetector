<?php
/**
 * Created by PhpStorm.
 * User: Thomas Müller
 * Date: 03.03.2016
 * Time: 07:22
 */

chdir(__DIR__);

require 'vendor/autoload.php';

ini_set('memory_limit', '-1');

$sourceDirectory = 'src\\Detector\\Os\\';

$iterator = new \RecursiveDirectoryIterator($sourceDirectory);

foreach (new \RecursiveIteratorIterator($iterator) as $file) {
    /** @var $file \SplFileInfo */
    if (!$file->isFile() || $file->getExtension() !== 'php') {
        continue;
    }

    $fullpath    = $file->getPathname();
    $pathMatches = [];

    $filecontent      = file_get_contents($fullpath);
    $marketingMatches = [];

    $fullpath    = $file->getPathname();
    $pathMatches = [];

    $filecontent      = file_get_contents($fullpath);
    $marketingMatches = [];

    if (!preg_match('/\\$this\\-\\>name         = \\\'([^\\\']+)/', $filecontent, $marketingMatches)) {
        continue;
    }

    echo 'processing ', $fullpath, PHP_EOL;

    $marketingMatches = [];

    if (preg_match('/\\$this\\-\\>name         = \\\'([^\\\']+)/', $filecontent, $marketingMatches)) {
        $manufacturer = $marketingMatches[1];
    } else {
        $manufacturer = 'Unknown';
    }

    $templateContent = str_replace(
        '$this->name         = \'' . $manufacturer . '\'',
        '$this->name         = \'' . $manufacturer . '\';' . "\n" . '        $this->marketingName = \'' . $manufacturer . '\'',
        $filecontent
    );

    file_put_contents($fullpath, $templateContent);

    if (false !== strpos($templateContent, '#')) {
        echo 'placeholders found in file ', $fullpath, PHP_EOL;
        exit;
    }
    //exit;
}