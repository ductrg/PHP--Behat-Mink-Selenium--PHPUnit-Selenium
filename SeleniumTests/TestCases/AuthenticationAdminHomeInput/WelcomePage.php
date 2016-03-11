<?php
namespace TestCases\AuthenticationAdminHomeInput;

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class WelcomePage
{
    protected $header = null;
    protected $cleanSessionButton = null;
    protected $logInSuccessfulMessage = null;
    protected $test = null;

    public function __construct($test)
    {
        try {
            $this->test = $test;
            $this->header = $test->byCssSelector("body>h2");
            $this->cleanSessionButton = $test->byXPath("//div/a[@tite='Logout']");
            $this->logInSuccessfulMessage = "//div[normalize-space()='@value@']";
        } catch (\PHPUnit_Extensions_Selenium2TestCase_WebDriverException $e) {
            throw new \Exception("\n\nEither user doesn't stay at Welcome page or the expected element is not there. Take a look at screenshot.\n\n" . $e->getMessage());
        }
    }

    public function assertPageHeaderIs($text)
    {
        $this->test->assertEquals($text, $this->header->text());
    }

    private function replace($text)
    {
        return str_replace('@value@', $text, $this->logInSuccessfulMessage);
    }

    public function assertLogInSuccessfullyText($text)
    {
        $valueLogInSuccessfulMessage = $this->replace($text);
        $this->test->assertEquals($text, $this->test->byXPath($valueLogInSuccessfulMessage)->text());
    }

    public function logOutExpectedOK()
    {
        $this->cleanSessionButton->click();
        $this->test->frame(NULL);
        return new AuthenticationPage($this->test);
    }
}