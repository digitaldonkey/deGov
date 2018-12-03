@api @drupal
Feature: deGov - Demo Content

  Background:
    Given I am installing the following Drupal modules:
      | degov_demo_content |

  Scenario: Check if all teasers will be displayed
    Given I am logged in as a user with the "administrator" role
    And I am on "/degov-demo-content/page-all-teasers"
    And I should see "Page with text paragraph"
    And I should see "Page with download paragraph"
    And I should see "Page with iframe paragraph"
    And I should see "Page with map paragraph"
    And I should see "Page with FAQ-List paragraph"
    And I should see "Page with video header"
    And I should see "Page with slideshow"
    And I should see "Page with banner"
    And I should see "TEASER - SMALL IMAGE"
    And I should see "TEASER - LONG TEXT"
    And I should see "TEASER - SLIM"
    And I should see "TEASER - PREVIEW"

  Scenario: Check for missing fields
    Given I am logged in as a user with the "administrator" role
    And I am on "/degov-demo-content/page-banner"
    And I should see "Page with banner"
    And I should see "A page with an image header"
    And I should see "degov_demo_content"

  Scenario: Check that FAQ paragraphs have answers
    Given I am on "/degov-demo-content/page-faq-list-paragraph"
    Then I proof css ".faq_question" contains text
    And I proof css ".faq_answer" contains text

