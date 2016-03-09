<?php
//namespace SeleniumTests\TestCases\AuthenticationAdminHomeInput;
//
//use SeleniumTests\BaseTests;

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class WelcomePage
{
    protected $header = null;
    protected $iconProfile = null;
    protected $userName = null;
    protected $logOutButton = null;
    protected $logInHeader = null;
    protected $test = null;

    public function __construct($test)
    {
        try {
            $this->header = $test->byCssSelector(".page-content");
            $this->iconProfile = $test->byXPath("//i[text()='account_circle']");
            $this->logOutButton = $test->byXPath("//i[text()='power_settings_new']");
            $this->logInHeader = "//h1[text()='@value@']";
            $this->test = $test;
        } catch (\PHPUnit_Extensions_Selenium2TestCase_WebDriverException $e) {
            throw new \Exception("\n\nEither user doesn't stay at Welcome page or the expected element is not there. Take a look at screenshot.\n\n" . $e->getMessage());
        }
    }

    public function assertPageHeaderIs($text)
    {
        $this->test->assertEquals($text, $this->header->text());
    }

    public function assertUsername($text)
    {
        $this->test->prepareSession()->currentWindow()->size(array('width' => 1920, 'height' => 1080));
        $this->test->assertEquals($text, $this->userName->text());
    }

    public function replace($text)
    {
        return str_replace('@value@', $text, $this->logInHeader);
    }

    public function assertLogInHeaderNotPresents($text)
    {
        $valueLogInHeader = $this->replace($text);
        $this->test->assertTextNotPresents($valueLogInHeader, 'XPATH');
    }

    public function logOutExpectedOK()
    {
        $this->iconProfile->click();
        $this->logOutButton->click();
        return new AuthenticationPage($this->test);
    }
}