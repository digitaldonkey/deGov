@api @drupal @view_mode
Feature: deGov - Teaser view modes

  Background:
    Given I am installing the "degov_paragraph_node_reference" module

  Scenario: I proof paragraph type node reference offers the specified view modes
    Given I have dismissed the cookie banner if necessary
    And I am logged in as a user with the "administrator" role
    And I am on "/node/add/normal_page"
    Then I choose "Content" via translation from tab menu
    And I press the "edit-field-content-paragraphs-add-more-add-modal-form-area-add-more" button
    And I trigger the "mousedown" event on ".ui-dialog [name=field_content_paragraphs_node_reference_add_more]"
    And I should see text matching "Viewmode der Teaser" after a while
    Then I assert dropdown named "field_content_paragraphs[0][subform][field_node_reference_viewmode]" contains the following text-value pairs:
      | text                | value       |
      | Teaser kleines Bild | small_image |
      | Teaser langer Text  | long_text   |
      | Teaser schmal       | slim        |
      | Vorschau            | preview     |
