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
            ->inputUsername('admin')
            ->inputPassword('123456')
            ->submitExpectedOK();

        // Verify header of log in page is not present
        $welcomePage->assertLogInHeaderNotPresents('Please login');
    }
}
