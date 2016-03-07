<?php
use Behat\MinkExtension\Context\RawMinkContext;

/**
 * Wikipedia steps context.
 */
class WikipediaContext extends RawMinkContext
{
    public function __construct($browserWidth, $browserHeight)
    {
        $this->browserWidth = $browserWidth;
        $this->browserHeight = $browserHeight;
    }

    /**
     *
     * @Given /^(?:|I )am on Wiki main page$/
     */
    public function accessWikiMainPage()
    {
        $this->visitPath("https://en.wikipedia.org/wiki/Main_Page");
        $this->getSession()->resizeWindow($this->browserWidth, $this->browserHeight, 'current');
    }

    /**
     *
     * @When /^(?:|I )click on Contents link$/
     */
    public function accessWikiContentsPage()
    {
        $this->getSession()->getPage()->clickLink('Contents');
    }

    /**
     *
     * @Then /^(?:|I )should be redirected to Contents page$/
     */
    public function checkWikiContentsPage()
    {
        $this->assertSession()->addressEquals('https://en.wikipedia.org/wiki/Portal:Contents');
        $this->assertSession()->elementTextContains('css', 'h1', 'Contents');
    }
}