<?php
namespace TestCases\AdminHomePage;

use TestCases\AuthenticationAdminHomeInput\WelcomePage;
use src\BaseTests;

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class VerifyLogOutSuccessfullyTest extends BaseTests
{
    public function testLogOutSuccessfully()
    {
        // Do login
        $this->logInSuccessfully();

        //Do logout
        $page = new WelcomePage($this);
        $welcomePage = $page->logOutExpectedOK();

        // Verify header of log in page
        $this->assertTextNotPresents("//div[normalize-space()='You have entered valid use name and password']", "XPATH");
    }
}