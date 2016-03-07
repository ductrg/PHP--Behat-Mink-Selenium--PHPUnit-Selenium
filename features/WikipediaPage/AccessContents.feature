Feature: Access contents successfully action
  As a anonymous user who access wiki main page
  I can access to content section
  So that I'm able to find content I want

  Rules:
  When user click on content link, system redirects him to content page

  @javascript
  Scenario: Access contents successfully (flow-like scenario)
    Given I am on "https://en.wikipedia.org/wiki/Main_Page" and make window larger
    When I click on text of "Contents"
    Then I should be on "https://en.wikipedia.org/wiki/Portal:Contents"
    And I should see "Portal:Contents" in the "h1" element

  @javascript
  Scenario: Access contents successfully (high level scenario)
    Given I am on Wiki main page
    When I click on Contents link
    Then I should be redirected to Contents page