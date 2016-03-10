<?php
namespace TestCases\AuthenticationPage;

use TestCases\AuthenticationAdminHomeInput\AuthenticationPage;
use src\BaseTests;

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class VerifyLogInUnsuccessfullyWithWrongUsernameTest extends BaseTests
{
    public function testLogInUnsuccessfullyWithWrongUsername()
    {
        $page = new AuthenticationPage($this);
        $authenticationPage = $page->clearUsername()
            ->clearPassword()
            ->inputUsername('testadmin')
            ->inputPassword('123456')
            ->submitExpectedNotOK();

        // Verify there is error message of wrong authentication
        $authenticationPage->assertErrorMessageOfWrongAuthentication('Bad credentials');

        // Verify header of log in page
        $authenticationPage->assertPageHeaderIs('Please login');
    }
}