<?php
namespace src;

/*        
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class BaseTests extends \PHPUnit_Extensions_Selenium2TestCase
{
    protected $coverageScriptUrl = 'http://127.0.0.1:4444';

    public static $browsers = array(
        array(
            'browserName' => 'firefox',
        ),
        //     array(
        //         'browserName' => 'chrome',
        //     ),
    );

    public function setUp()
    {
        $this->setHost(PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_HOST);
        $this->setPort((int)PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_PORT);
        $seleniumUrl = static::getUrl();

        if (empty($seleniumUrl)) {
            $this->markTestSkipped(
                "You must serve the selenium-1-tests folder from an HTTP server
                and configure the PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_TESTS_URL constant accordingly."
            );
        }
        $this->setBrowserUrl($seleniumUrl);
        \PHPUnit_Extensions_Selenium2TestCase::shareSession(true);
    }

    public static function getUrl()
    {
        //Default get from phpunit.xml file
        $seleniumUrl = PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_TESTS_URL;

        //try to get from ENV
        $envSeleniumUrl = @getenv('PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_TESTS_URL');

        //if ENV available
        if (!empty($envSeleniumUrl)) {
            //Allow override $seleniumUrl by environment
            $seleniumUrl = $envSeleniumUrl;
        }

        //return the Url
        return $seleniumUrl;
    }

    // Verify element is not present
    public function assertTextNotPresents($key, $type)
    {
        switch ($type) {
            case 'XPATH':
                try {
                    $this->prepareSession();
                    $text = $this->byXPath($key)->text();
                    $this->fail("Failed: " . $text . " " . " shouldn't exist\n");
                } catch (\PHPUnit_Extensions_Selenium2TestCase_WebDriverException $e) {
                    $this->assertEquals(
                        \PHPUnit_Extensions_Selenium2TestCase_WebDriverException::NoSuchElement,
                        $e->getCode()
                    );
                }
                break;
            case 'CSS':
                try {
                    $this->prepareSession();
                    $text = $this->byCssSelector($key)->text();
                    $this->fail("Failed: " . $text . " " . " shouldn't exist\n");
                } catch (\PHPUnit_Extensions_Selenium2TestCase_WebDriverException $e) {
                    $this->assertEquals(
                        \PHPUnit_Extensions_Selenium2TestCase_WebDriverException::NoSuchElement,
                        $e->getCode()
                    );
                }
                break;
            case 'className':
                try {
                    $this->prepareSession();
                    $text = $this->byClassName($key)->text();
                    $this->fail("Failed: " . $text . " " . " shouldn't exist\n");
                } catch (\PHPUnit_Extensions_Selenium2TestCase_WebDriverException $e) {
                    $this->assertEquals(
                        \PHPUnit_Extensions_Selenium2TestCase_WebDriverException::NoSuchElement,
                        $e->getCode()
                    );
                }
                break;
            case 'linkText':
                try {
                    $this->prepareSession();
                    $text = $this->byLinkText($key)->text();
                    $this->fail("Failed: " . $text . " " . " shouldn't exist\n");
                } catch (\PHPUnit_Extensions_Selenium2TestCase_WebDriverException $e) {
                    $this->assertEquals(
                        \PHPUnit_Extensions_Selenium2TestCase_WebDriverException::NoSuchElement,
                        $e->getCode()
                    );
                }
                break;
            case 'id':
                try {
                    $this->prepareSession();
                    $text = $this->byId($key)->text();
                    $this->fail("Failed: " . $text . " " . " shouldn't exist\n");
                } catch (\PHPUnit_Extensions_Selenium2TestCase_WebDriverException $e) {
                    $this->assertEquals(
                        \PHPUnit_Extensions_Selenium2TestCase_WebDriverException::NoSuchElement,
                        $e->getCode()
                    );
                }
                break;
            case 'name':
                try {
                    $this->prepareSession();
                    $text = $this->byName($key)->text();
                    $this->fail("Failed: " . $text . " " . " shouldn't exist\n");
                } catch (\PHPUnit_Extensions_Selenium2TestCase_WebDriverException $e) {
                    $this->assertEquals(
                        \PHPUnit_Extensions_Selenium2TestCase_WebDriverException::NoSuchElement,
                        $e->getCode()
                    );
                }
                break;
            case 'tag':
                try {
                    $this->prepareSession();
                    $text = $this->byTag($key)->text();
                    $this->fail("Failed: " . $text . " " . " shouldn't exist\n");
                } catch (\PHPUnit_Extensions_Selenium2TestCase_WebDriverException $e) {
                    $this->assertEquals(
                        \PHPUnit_Extensions_Selenium2TestCase_WebDriverException::NoSuchElement,
                        $e->getCode()
                    );
                }
                break;
            default:
                $this->fail("Either locator is empty or invalid");
                break;
        }
    }

    // Do log in pre-requisition
    public function logInSuccessfully()
    {
        $this->url('/php/php_login_example.htm');
        $this->prepareSession()->currentWindow()->size(array('width' => 1920, 'height' => 1080));

        // Switch to iframe of login form
        $this->switchIframe(1);

        // Clear existing data
        $usernameInput = $this->byName('username');
        $usernameInput->clear();
        $passwordInput = $this->byName('password');
        $passwordInput->clear();

        // Input valid credentials and submit
        $this->byName('username')->value(PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_TESTS_USERNAME);
        $this->byName('password')->value(PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_TESTS_PASSWORD);
        $this->byName('login')->click();
    }

    // Switch to an iframe
    public function switchIframe($element)
    {
        $this->frame($element);
    }

    // Override PHPUnit_Extensions_Selenium2TestCase::onNotSuccessfulTest
    public function onNotSuccessfulTest(\Exception $e)
    {
        // Capture screenshot whenever a test is failed
        $fileData = $this->currentScreenshot();
        $class = explode("\\", get_class($this));
        $file = __DIR__ . '/../screenshots/' . date('h-i-s,j-m-y', time())
            . '_' . $class[count($class) - 1] . '_' . $this->getBrowser() . '.png';
        file_put_contents($file, $fileData);

        parent::onNotSuccessfulTest($e);
    }
}