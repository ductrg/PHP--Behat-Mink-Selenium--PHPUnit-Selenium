<?php

use Behat\MinkExtension\Context\MinkContext;

/**
 * Defines application features from the specific context.
 */
class FeatureContext extends MinkContext
{
    const CIID = 'TRAVIS_BUILD_ID';
    const CIBRANCH = 'TRAVIS_BRANCH';
    const CIBUILDNUM = 'TRAVIS_BUILD_NUMBER';

    static private $randomCharacter = null;

    public function __construct($browserWidth, $browserHeight)
    {
        $this->browserWidth = $browserWidth;
        $this->browserHeight = $browserHeight;
    }

    /**
     * Click on element with specified css selector.
     *
     * @When /^(?:|I )click on "(?P<selector>(?:[^"]|\\")*)"$/
     */
    public function clickCSS($selector)
    {
        $this->clickType('css', $selector);
    }

    protected function clickType($type, $selector)
    {
        $selector = $this->DoFixStepArgument($selector);
        $element = $this->getSession()->getPage()->find($type, $selector);

        if (null === $element) {
            throw new \InvalidArgumentException(sprintf('Could not find selector: "%s"', $selector));
        }

        $element->click();
    }

    /**
     * Click on element with specified xpath selector.
     *
     * @When /^(?:|I )click on element xpath "(?P<selector>(?:[^"]|\\")*)"$/
     */
    public function clickXpath($selector)
    {
        $this->clickType('xpath', $selector);
    }

    /**
     * Waits some time.
     *
     * @When /^(?:|I )wait for "(?P<time>(?:[^"]|\\")*)" seconds$/
     */
    public function wait($time)
    {
        $time = $time * 1000;
        $this->getSession()->wait($time);
    }

    /**
     * Wait for AJAX to finish.
     *
     * @When /^(?:|I )wait for page loading$/
     */
    public function waitForAjax()
    {
        $this->getSession()->wait(10000, '(typeof(jQuery)=="undefined"
        || (0 === jQuery.active && 0 === jQuery(\':animated\').length))');
    }

    /**
     * Wait for a pop up processing to finish.
     *
     * @When /^(?:|I )wait for element "(?P<selector>[^"]+)" disappears$/
     */
    public function waitForPopUp($selector)
    {
        $i = 0;
        $element = $this->getSession()->getPage()->find('css', $selector);

        if (null === $element) {
            throw new \InvalidArgumentException(sprintf('Could not find selector: "%s"', $selector));
        }
        while ($i < 15) {
            try {
                if (($element->isVisible()) != 1) {
                    return;
                }
            } catch (Exception $e) {
                // case of system throwing exception element is not visible
                return;
            }
            sleep(1);
            ++$i;
        }
    }

    /**
     * Opens specified page and make window larger.
     *
     * @Given /^(?:|I )am on "(?P<page>[^"]+)" and make window larger$/
     * @When /^(?:|I )go to "(?P<page>[^"]+)" and make window larger$/
     */
    public function navigate($page)
    {
        $this->visitPath($page);
        $this->getSession()->resizeWindow($this->browserWidth, $this->browserHeight, 'current');
    }

    /**
     * Opens specified page and maximize window.
     *
     * @Given /^(?:|I )am on "(?P<page>[^"]+)" and maximize window$/
     * @When /^(?:|I )go to "(?P<page>[^"]+)" and maximize window$/
     */
    public function navigateMax($page)
    {
        $this->visitPath($page);
        $this->getSession()->maximizeWindow();
    }

    /**
     * Verify if the given attribute value of element is correct or not.
     *
     * @Then /^(?:|I )should see value "(?P<value>(?:[^"]|\\")*)" of attribute "(?P<attr>[^"]+)" of the element "(?P<selector>[^"]+)" displays$/
     */
    public function verifyAttribute($value, $attr, $selector)
    {
        $this->verifyForType('css', $value, $attr, $selector);
    }

    protected function verifyForType($type, $value, $attr, $selector)
    {
        // locate element
        $element = $this->getSession()->getPage()->find($type, $selector);
        if (null === $element) {
            throw new \InvalidArgumentException(sprintf('Could not find selector: "%s"', $selector));
        }

        // locate attribute of the element
        $attribute = $element->getAttribute($attr);
        if (null === $attribute) {
            throw new \InvalidArgumentException(
                sprintf('Could not find attribute: "%s" of selector: "%s"', $attr, $selector)
            );
        }

        // compare value of attribute
        $message = sprintf(
            'The actual value "%s" was not correct in compared with the expected value %s of the selector %s.',
            $attribute,
            $value,
            $selector
        );
        if ($attribute != $value) {
            throw new \Exception($message);
        }
    }

    /**
     * Verify if the given attribute value of element is correct or not.
     *
     * @Then /^(?:|I )should see value "(?P<value>(?:[^"]|\\")*)" of attribute "(?P<attr>[^"]+)" of the element xpath "(?P<selector>[^"]+)" displays$/
     */
    public function verifyAttributeXpath($value, $attr, $selector)
    {
        $this->verifyForType('xpath', $value, $attr, $selector);
    }

    /**
     * Click on the given text
     *
     * @When /^(?:|I )click on text of "(?P<text>[^"]+)"$/
     */
    public function clickText($text)
    {
        // locate element
        $session = $this->getSession();
        $element = $session->getPage()->find(
            'xpath',
            $session->getSelectorsHandler()->selectorToXpath('xpath', '//*[text()="' . $text . '"]')
        );
        if (null === $element) {
            throw new \InvalidArgumentException(sprintf('Could not find text: "%s"', $text));
        }

        // click
        $element->click();
    }

    /**
     * Confirm yes at the popup
     *
     * @When /^(?:|I )confirm the pop up$/
     */
    public function confirmPopup()
    {
        $this->getSession()->getDriver()->getWebDriverSession()->accept_alert();
    }

    /**
     * Random-value fills in form field with specified id|name|label|value.
     *
     * @When /^(?:|I )fill in "(?P<field>(?:[^"]|\\")*)" with value of "(?P<value>(?:[^"]|\\")*)"$/
     */
    public function fillFieldRandom($field, $value)
    {
        $field = $this->DoFixStepArgument($field);
        $value = $this->DoFixStepArgument($value);

        //create the random letters by CI env variables
        $number = $this->doJoinVariable();

        //complete the random characters required
        $randomValue = $this->caseFieldRandom($value, $number);

        $this->getSession()->getPage()->fillField($field, $randomValue);
    }

    protected function caseFieldRandom($value, $number)
    {
        if (strstr($value, 'random name with prefix ')) {
            $value = str_replace('random name with prefix ', "", $value) . $number;
        }
        if (strstr($value, 'random email with prefix ')) {
            $value = str_replace('random email with prefix ', "", $value) . $number . "@test.net";
        }
        if (strstr($value, 'random 3 capital characters')) {
            static::$randomCharacter = substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 3);
            $value = static::$randomCharacter;
        }
        return $value;
    }

    /**
     * Checks, that element with specified Xpath contains specified text.
     *
     * @Then /^(?:|I )should see "(?P<text>(?:[^"]|\\")*)" in the "(?P<element>[^"]*)" element xpath$/
     */
    public function assertElementContainsTextByXpath($element, $text)
    {
        $this->assertSession()->elementTextContains('xpath', $element, $this->DoFixStepArgument($text));
    }

    /**
     * Checks, that page contain specified text generated randomly base on fillFieldRandom.
     *  Note: to use per time run , for TRAVIS only. Can not use between different time run.
     * @Then /^(?:|I )should see that random text "(?P<value>(?:[^"]|\\")*)"$/
     */
    public function assertPageContainsRandomText($value)
    {
        $this->assertRandomText('contain', $value);
    }

    protected function assertRandomText($type, $value)
    {
        $value = $value . $this->doJoinVariable();

        if (strstr($value, 'with prefix ') && $type == 'doesntContain') {
            $value = str_replace('with prefix ', "", $value);
            $this->assertSession()->pageTextNotContains($this->DoFixStepArgument($value));

        } elseif (strstr($value, '3 capital characters') && $type == 'doesntContain') {
            $this->assertSession()->pageTextNotContains($this->DoFixStepArgument(static::$randomCharacter));

        } elseif (strstr($value, 'with prefix ') && $type == 'contain') {
            $value = str_replace('with prefix ', "", $value);
            $this->assertSession()->pageTextContains($this->DoFixStepArgument($value));

        } elseif (strstr($value, '3 capital characters') && $type == 'contain') {
            $this->assertSession()->pageTextContains($this->DoFixStepArgument(static::$randomCharacter));

        } else {
            throw new \InvalidArgumentException(sprintf('This text "%s" is not supported', $value));
        }
    }

    /**
     * Checks, that page doesn't contain specified text generated randomly base on fillFieldRandom.
     *  Note: to use per time run , for TRAVIS only. Can not use between different time run.
     * @Then /^(?:|I )should not see that random text "(?P<value>(?:[^"]|\\")*)"$/
     */
    public function assertPageNotContainsRandomText($value)
    {
        $this->assertRandomText('doesntContain', $value);
    }

    /**
     * Scroll to a specific element ID to make it visible
     *
     * @When /^(?:|I )scroll element id "(?P<elementId>[^"]+)" into view$/
     */
    public function scrollIntoView($elementId)
    {
        $function = <<<JS
            (function(){
              var elem = document.getElementById("$elementId");
              elem.scrollIntoView();
            })()
JS;
        try {
            $this->getSession()->executeScript($function);
        } catch (Exception $e) {
            throw new \Exception("Scroll into view failed: " . $e->getMessage());
        }
    }

    protected function doJoinVariable()
    {
        $number = getenv(static::CIBRANCH) . getenv(static::CIBUILDNUM) . getenv(static::CIID);
        if (empty($number)) {
            $number = time();
        }
        return $number;
    }

    /**
     * Returns the fixed step argument (with \\" replaced back to ").
     *
     * @param string $argument
     *
     * @return string
     */
    protected function doFixStepArgument($argument)
    {
        return str_replace('\\"', '"', $argument);
    }
}
