<?php
namespace TestCases\AuthenticationPage;

use TestCases\AuthenticationAdminHomeInput\AuthenticationPage;
use src\BaseTests;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class VerifyLogInSuccessfullyTest extends BaseTests
{
    public function testLogInSuccessfully()
    {
        $page = new AuthenticationPage($this);
        $welcomePage = $page->clearUsername()
            ->clearPassword()
            ->inputUsername('tutorialspoint')
            ->inputPassword('1234')
            ->submitExpectedOK();

        // Verify page header
        $welcomePage->assertPageHeaderIs('Enter Username and Password');

        // Verify header of log in page is present
        $welcomePage->assertLogInSuccessfullyText('You have enter1ed valid use name and password');
    }
}
