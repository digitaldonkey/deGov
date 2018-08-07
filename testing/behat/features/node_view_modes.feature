@api @drupal
Feature: deGov view modes

  Background:
    Given I am installing the following Drupal modules:
      | simplenews                        |
      | degov_node_press                  |
      | degov_taxonomy_term               |
      | degov_taxonomy_term_section       |
      | degov_content_types_shared_fields |
      | degov_simplenews                  |
      | degov_node_blog                   |
      | degov_node_event                  |
    Given I proof that the following Drupal modules are installed:
      | degov_node_normal_page |
      | degov_node_press       |
    Given the cache has been cleared

  Scenario: Content type normal_page has necessary view modes
    Given I am logged in as a user with the "administrator" role
    And I am on "/admin/structure/types/manage/normal_page/display"
    Then I should not see text matching "Error"
    And I should not see text matching "Warning"
    Then I should see text matching "Default"
    And I should see text matching "Teaser langer Text"
    And I should see text matching "Teaser Preview"
    And I should see text matching "Slideshow"
    And I should see text matching "Teaser schmal"
    And I should see text matching "Teaser kleines Bild"
    And I should see text matching "Anrisstext"

  Scenario: Content type blog has necessary view modes
    Given I am logged in as a user with the "administrator" role
    And I am on "/admin/structure/types/manage/blog/display"
    Then I should not see text matching "Error"
    And I should not see text matching "Warning"
    Then I should see text matching "Default"
    And I should see text matching "Teaser langer Text"
    And I should see text matching "Teaser Preview"
    And I should see text matching "Slideshow"
    And I should see text matching "Teaser schmal"
    And I should see text matching "Teaser kleines Bild"
    And I should see text matching "Anrisstext"

  Scenario: Content type press has necessary view modes
    Given I am logged in as a user with the "administrator" role
    And I am on "/admin/structure/types/manage/press/display"
    Then I should not see text matching "Error"
    And I should not see text matching "Warning"
    Then I should see text matching "Default"
    And I should see text matching "Latest"
    And I should see text matching "Teaser langer Text"
    And I should see text matching "Teaser Preview"
    And I should see text matching "Slideshow"
    And I should see text matching "Teaser schmal"
    And I should see text matching "Teaser kleines Bild"
    And I should see text matching "Anrisstext"

  Scenario: Content type event has necessary view modes
    Given I am logged in as a user with the "administrator" role
    And I am on "/admin/structure/types/manage/event/display"
    Then I should not see text matching "Error"
    And I should not see text matching "Warning"
    Then I should see text matching "Default"
    And I should see text matching "Latest"
    And I should see text matching "Teaser langer Text"
    And I should see text matching "Teaser Preview"
    And I should see text matching "Slideshow"
    And I should see text matching "Teaser schmal"
    And I should see text matching "Teaser kleines Bild"
    And I should see text matching "Anrisstext"

  Scenario: Content type simplenews_issue has necessary view modes
    Given I am logged in as a user with the "administrator" role
    Then I am on "/admin/structure/types/manage/simplenews_issue/display"
    And I should not see text matching "Error"
    And I should not see text matching "Warning"
    Then I should see HTML content matching "Default"
    And I should see HTML content matching "Anrisstext"
    And I should see HTML content matching "E-Mail: HTML"
    And I should see HTML content matching "E-Mail: Nur-Text"
    And I should see HTML content matching "E-Mail: HTML Text Alternative"