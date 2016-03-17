<?php
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\MinkExtension\Context\RawMinkContext;

/**
 * Google steps context.
 */
class GoogleContext extends RawMinkContext
{
    public function __construct($browserWidth, $browserHeight)
    {
        $this->browserWidth = $browserWidth;
        $this->browserHeight = $browserHeight;
    }

    /** @var FeatureContext */
    private $FeatureContext;

    /** @BeforeScenario */
    public function gatherContexts(BeforeScenarioScope $scope)
    {
        $environment = $scope->getEnvironment();
        $this->FeatureContext = $environment->getContext('FeatureContext');
    }

    /**
     *
     * @Given /^(?:|I )am on Google search page$/
     */
    public function accessGoogleSearch()
    {
        $this->visitPath("http://www.google.com");
        $this->getSession()->resizeWindow($this->browserWidth, $this->browserHeight, 'current');
    }

    /**
     *
     * @When /^(?:|I )enter a valid text to search$/
     */
    public function doGoogleSearch()
    {
        $this->getSession()->getPage()->fillField('q', 'wikipedia');
        $this->getSession()->getPage()->pressButton('Search');
        $this->FeatureContext->wait('2');
    }

    /**
     *
     * @Then /^(?:|I )should see the related results according to the text at result page$/
     */
    public function checkGoogleSearchResult()
    {
        $this->assertSession()->addressEquals($this->locatePath('#q=wikipedia'));
        $this->assertSession()->pageTextContains('Wiki - Wikipedia, the free encyclopedia');
    }
}