<?php
namespace TestCases\AuthenticationAdminHomeInput;

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class AuthenticationPage
{
    protected $errorMessageOfWrongAuthentication = null;
    protected $errorMessage = null;
    protected $logInSuccessfulMessage = null;
    protected $usernameInput = null;
    protected $passwordInput = null;
    protected $login = null;
    protected $test = null;

    public function __construct($test)
    {
        try {
            $test->url('/php/php_login_example.htm');
            $this->test = $test;
            $this->test->switchIframe(1);
            $this->errorMessageOfWrongAuthentication = ".form-signin-heading";
            $this->errorMessage = "//h4[text()='@value']";
            $this->logInSuccessfulMessage = "//div[normalize-space()='@value@']";
            $this->usernameInput = $test->byName('username');
            $this->passwordInput = $test->byName('password');
            $this->login = $test->byName('login');
        } catch (\PHPUnit_Extensions_Selenium2TestCase_WebDriverException $e) {
            throw new \Exception("\n\nEither user doesn't stay at Authentication page or the expected element is not there. Take a look at screenshot.\n\n" . $e->getMessage());
        }
    }

    public function inputUsername($value)
    {
        $this->usernameInput->value($value);
        return $this;
    }

    public function clearUsername()
    {
        $this->usernameInput->clear();
        return $this;
    }

    public function inputPassword($value)
    {
        $this->passwordInput->value($value);
        return $this;
    }

    public function clearPassword()
    {
        $this->passwordInput->clear();
        return $this;
    }

    public function submitExpectedOK()
    {
        $this->login->click();
        return new WelcomePage($this->test);
    }

    public function submitExpectedNotOK()
    {
        $this->login->click();
        return $this;
    }

    public function assertErrorMessageOfWrongAuthentication($text)
    {
        try {
            $this->test->assertEquals($text, $this->test->byCssSelector($this->errorMessageOfWrongAuthentication)->text());
        } catch (\PHPUnit_Extensions_Selenium2TestCase_WebDriverException $e) {
            throw new \Exception("\n\nEither user doesn't stay at Authentication page or the expected error message of the page is not there. Take a look at screenshot.\n\n" . $e->getMessage());
        }
    }

    private function replace($text, $message)
    {
        return str_replace('@value@', $text, $message);
    }

    public function assertLogInSuccessfullyTextIsNotPresent($text)
    {
        $valueLogInSuccessfulMessage = $this->replace($text, $this->logInSuccessfulMessage);
        $this->test->assertTextNotPresents($valueLogInSuccessfulMessage, 'XPATH');
    }

    public function assertErrorMessageIsNotPresent($text)
    {
        $valueErrorMessage = $this->replace($text, $this->errorMessage);
        $this->test->assertTextNotPresents($valueErrorMessage, 'XPATH');
    }
}