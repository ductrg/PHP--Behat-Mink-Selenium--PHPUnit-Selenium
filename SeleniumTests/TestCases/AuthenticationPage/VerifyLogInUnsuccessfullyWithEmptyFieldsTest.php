<?php
//namespace SeleniumTests\TestCases\AuthenticationPage;
//
//use SeleniumTests\TestCases\AuthenticationAdminHomeInput\AuthenticationPage;
//use SeleniumTests\BaseTests;

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class VerifyLogInUnsuccessfullyWithEmptyFieldsTest extends BaseTests
{
    public function testLogInUnsuccessfullyWithEmptyFields()
    {
        $page = new AuthenticationPage($this);
        $authenticationPage = $page->clearUsername()
            ->clearPassword()
            ->submitExpectedNotOK();

        // Verify there is error message of empty field
        $authenticationPage->assertErrorMessageOfWrongAuthentication('Bad credentials');

        // Verify header of log in page
        $authenticationPage->assertPageHeaderIs('Please login');
    }
}