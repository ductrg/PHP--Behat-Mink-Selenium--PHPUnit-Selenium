Feature: Search successfully action
  As a anonymous user who access google search page
  I want do a search by entering my text
  So that I can find related results to the text

  Rules:
  When I enter valid text to search such as 'wikipedia', I should see related result according to the text

  @javascript
  Scenario: search successfully (flow-like scenario)
    Given I am on "/" and make window larger
    When I fill in "q" with "wikipedia"
    And I wait for page loading
    And I press "Search"
    And I wait for "1" seconds
    Then I should be on "#q=wikipedia"
    And I should see "Wikipedia - Wikipedia, the free encyclopedia"

  @javascript
  Scenario: search successfully (high level scenario)
    Given I am on Google search page
    When I enter a valid text to search
    Then I should see the related results according to the text at result page