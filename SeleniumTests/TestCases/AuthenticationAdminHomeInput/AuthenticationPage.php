<?php
//namespace SeleniumTests\TestCases\AuthenticationAdminHomeInput;

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class AuthenticationPage
{
    protected $header = null;
    protected $errorMessageOfWrongAuthentication = null;
    protected $usernameInput = null;
    protected $passwordInput = null;
    protected $login = null;
    protected $test = null;

    public function __construct($test)
    {
        try {
            $test->url('/login');
            $this->header = "//h3";
            $this->errorMessageOfWrongAuthentication = ".errors.login-errors";
            $this->usernameInput = $test->byName('_username');
            $this->passwordInput = $test->byName('_password');
            $this->login = $test->byName('login');
            $this->test = $test;
        } catch (\PHPUnit_Extensions_Selenium2TestCase_WebDriverException $e) {
            throw new \Exception("\n\nEither user doesn't stay at Authentication page or the expected element is not there. Take a look at screenshot.\n\n" . $e->getMessage());
        }
    }

    public function assertPageHeaderIs($text)
    {
        try {
            $this->test->assertEquals($text, $this->test->byXPath($this->header)->text());
        } catch (\PHPUnit_Extensions_Selenium2TestCase_WebDriverException $e) {
            throw new \Exception("\n\nEither user doesn't stay at Authentication page or the expected header of the page is not there. Take a look at screenshot.\n\n" . $e->getMessage());
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
}