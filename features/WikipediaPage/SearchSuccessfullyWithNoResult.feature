Feature: Access contents successfully action
  As a anonymous user who access wiki main page
  I can  do searching
  So that I'm able to find result I want

  Rules:
  When user enters search text, system returns him the according results
  When user enters search email, system returns him the according results

  Background:
  Given I am on "https://en.wikipedia.org/wiki/Main_Page" and maximize window

  @javascript
  Scenario: Access contents successfully (flow-like scenario)
    When I fill in "Search" with value of "random name with prefix Abc"
    And I click on "#searchButton"
    Then I should see that random "name"

    When I fill in "Search" with value of "random email with prefix Def"
    And I click on "#searchButton"
    Then I should see that random "mail"

    When I fill in "Search" with value of "random 3 capital characters"
    And I click on "#searchButton"
    Then I should see that random "3 capital characters"