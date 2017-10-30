<?php

namespace TweetProxyBundle\Model;

/**
 * Class SimpleLogger
 * @package TweetProxyBundle\Model
 */
class SimpleLogger
{

    private $logPath;

    public function __construct () {
        $this->logPath = realpath(dirname(__FILE__) . '/../../../var/') . '/';
    }

    /**
     * @param $text
     * @param string $logFile
     */
    public function writeLn ($text, $logFile = 'tweetProxy') {

        if (!trim($logFile)) {
            $logFile = 'tweetProxy';
        }

        $logFile .= '.log';

        try {

            $currentDate = new \DateTime('now', new \DateTimeZone('UTC'));
            $fp = fopen($this->logPath . $logFile, "a");

            if ($fp) {
                fwrite($fp, $currentDate->format('d.m.Y H:i:s') . ' : ' . $text . "\r\n");
                fclose($fp);
            }

        }
        catch (\Exception $e) {}
    }

}