<?php
namespace TestCases\AuthenticationPage;

use TestCases\AuthenticationAdminHomeInput\AuthenticationPage;
use src\BaseTests;

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class VerifyLogInUnsuccessfullyWithWrongPassTest extends BaseTests
{
    public function testLogInUnsuccessfullyWithWrongPass()
    {
        $page = new AuthenticationPage($this);
        $authenticationPage = $page->clearUsername()
            ->clearPassword()
            ->inputUsername('tutorialspoint')
            ->inputPassword('12345')
            ->submitExpectedNotOK();

        // Verify there is error message of wrong authentication
        $authenticationPage->assertErrorMessageOfWrongAuthentication('Wrong username or password');
    }
}