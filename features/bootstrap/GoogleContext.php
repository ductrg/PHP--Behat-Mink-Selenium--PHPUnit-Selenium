<?php
use Behat\MinkExtension\Context\MinkContext;

/**
 * Preliminary steps context.
 */
class GoogleContext extends MinkContext
{
    public function __construct($browserWidth, $browserHeight)
    {
        $this->browserWidth = $browserWidth;
        $this->browserHeight = $browserHeight;
    }
}