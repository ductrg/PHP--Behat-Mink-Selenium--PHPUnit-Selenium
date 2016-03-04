Feature: Search successfully action
  As a normal user who access google search page
  I want do a search by entering my text
  So that I can find related results to the text

  Rules:
  When I enter valid text to search such as 'wikipedia', I should see related result according to the text

  @javascript
  Scenario: search successfully
    Given I am on "/" and maximize window
    When I fill in "q" with "wikipedia"
    And I wait for page loading
    And I press "Search"
    And I wait for "1" seconds
    Then I should be on "#q=wikipedia"
    And I should see "Wikipedia - Wikipedia, the free encyclopedia"