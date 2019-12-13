@api @drupal @entities
Feature: deGov - Menu editing

  Background:
    Given I proof that the following Drupal modules are installed:
      | link_attributes    |
      | media_file_links   |
      | degov_demo_content |

  Scenario: Checking CSS class specification in menu items
    Given I have dismissed the cookie banner if necessary
    And I am logged in as a user with the "administrator" role
    And I am on "/admin/structure/menu/manage/main/add"
    Then I should see HTML content matching "link[0][options][attributes][class]"

  Scenario: I check that menu items can contain Media file links and that they are resolved
    Given I configure and place the main menu block
    And I have dismissed the cookie banner if necessary
    And I am on the homepage
    Then I should not see HTML content matching "/sites/default/files/degov_demo_content/dummy.pdf"
    Then I am logged in as a user with the "administrator" role
    And I am on "/admin/structure/menu/manage/main/add"
    And I fill in "edit-title-0-value" with "Dummy"
    And I enter the menu placeholder for a "document" media file in "#edit-link-0-uri"
    And I scroll to the "#edit-submit" element
    And I press button with label "Save" via translated text
    And I am on the homepage
    Then I should see HTML content matching "/sites/default/files/degov_demo_content/dummy.pdf" after a while

  Scenario: I check that menu item autocomplete suggestions include Media entities
    Given I have dismissed the cookie banner if necessary
    And I am logged in as a user with the "administrator" role
    And I am on "/admin/structure/menu/manage/main/add"
    And I should not see HTML content matching "A document with a PDF [Dokument, dummy.pdf]"
    And I should not see HTML content matching "fa-file-alt"
    And I fill in "edit-link-0-uri" with "docu"
    And I trigger the "keydown" event on "#edit-link-0-uri"
    Then I should see HTML content matching "A document with a PDF [Dokument, dummy.pdf]" after a while
    And I should see HTML content matching "fa-file-alt"

  Scenario: I check that autocomplete suggestions for tags do not include Media entities
    Given I have dismissed the cookie banner if necessary
    And I am logged in as a user with the "administrator" role
    And I am on "/node/1/edit"
    Then I choose "Allgemein" from tab menu
    And I fill in "edit-field-tags-0-target-id" with "d"
    And I trigger the keydown event on "#edit-field-tags-0-target-id"
    Then I should see HTML content matching "degov_demo_content (6)" after a while
    Then I should not see HTML content matching "A document with a PDF [Dokument, dummy.pdf]"

  Scenario: I check that autocomplete suggestions for users do not include Media entities
    Given I have dismissed the cookie banner if necessary
    And I am logged in as a user with the "administrator" role
    And I am on "/admin/structure/taxonomy/manage/section/add"
    And I click by selector "#fieldset_term_access > summary" via JavaScript
    Then I should see text matching "To limit access to this term by" after a while
    And I fill in "edit-access-user" with "a"
    And I trigger the keydown event on "#edit-access-user"
    Then I should see HTML content matching "admin" after a while
    And I should not see HTML content matching "A document with a PDF [Dokument, dummy.pdf]"

  Scenario: I check that node autocomplete suggestions outside of menu links do not include Media entities
    Given I have dismissed the cookie banner if necessary
    And I am logged in as a user with the "administrator" role
    And I am on "/node/add/faq#edit-group-content"
    And I fill in "edit-field-faq-related-0-target-id" with "a"
    And I trigger the keydown event on "#edit-field-faq-related-0-target-id"
    Then I should see HTML content matching "FAQ demo" after a while
    And I should not see HTML content matching "A document with a PDF [Dokument, dummy.pdf]"
