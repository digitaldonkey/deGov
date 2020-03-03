@api @drupal @entities
Feature: Entity reference timer

  Background:
    Given I proof that the following Drupal modules are installed:
      | degov_demo_content     |
      | entity_reference_timer |
    Given I have dismissed the cookie banner if necessary

  Scenario: An entity reference element has a checkbox for scheduled publication
    And I am logged in as a user with the "administrator" role
    And I am on "/node/add/normal_page#edit-group-content"
    And I should see 1 "#edit-field-content-paragraphs-add-more-add-modal-form-area-add-more" elements after a while
    And I click by selector "#edit-field-content-paragraphs-add-more-add-modal-form-area-add-more" via JavaScript
    And I trigger the "mousedown" event on ".ui-dialog [name=field_content_paragraphs_node_reference_add_more]"
    Then I should see HTML content matching "publish time scheduled" via translated text after a while

  Scenario: An entity reference element shows start and end date after checkbox click
    And I am logged in as a user with the "administrator" role
    And I am on "/node/add/normal_page#edit-group-content"
    And I should see 1 "#edit-field-content-paragraphs-add-more-add-modal-form-area-add-more" elements after a while
    And I click by selector "#edit-field-content-paragraphs-add-more-add-modal-form-area-add-more" via JavaScript
    And I trigger the "mousedown" event on ".ui-dialog [name=field_content_paragraphs_node_reference_add_more]"
    Then I should see HTML content matching "publish time scheduled" via translated text after a while
    And I click checkbox by name attribute value "field_content_paragraphs[0][subform][field_node_reference_nodes][0][publish_timer]"
    And I should see HTML content matching "Start" via translated text after a while
    And I should see HTML content matching "End" via translated text after a while
    And I fill in "field_content_paragraphs[0][subform][field_node_reference_nodes][0][start_date][date]" with "11112019"
    And I fill in "field_content_paragraphs[0][subform][field_node_reference_nodes][0][start_date][time]" with "090909AM"
    And I assert input field with name attribute value "field_content_paragraphs[0][subform][field_node_reference_nodes][0][end_date][date]" is empty
    And I assert input field with name attribute value "field_content_paragraphs[0][subform][field_node_reference_nodes][0][end_date][time]" is empty

  Scenario: Default start date is reflecting the current date
    And I am logged in as a user with the "administrator" role
    And I am on "/node/add/normal_page#edit-group-content"
    And I should see 1 "#edit-field-content-paragraphs-add-more-add-modal-form-area-add-more" elements after a while
    And I click by selector "#edit-field-content-paragraphs-add-more-add-modal-form-area-add-more" via JavaScript
    And I trigger the "mousedown" event on ".ui-dialog [name=field_content_paragraphs_node_reference_add_more]"
    Then I should see HTML content matching "publish time scheduled" via translated text after a while
    And I click checkbox by name attribute value "field_content_paragraphs[0][subform][field_node_reference_nodes][0][publish_timer]"
    And I assert date field with name attribute value "field_content_paragraphs[0][subform][field_node_reference_nodes][0][start_date][date]" contains current date
    And I assert input field with name attribute value "field_content_paragraphs[0][subform][field_node_reference_nodes][0][end_date][date]" is empty

  Scenario: I prove that the end date must be after the start date
    And I am logged in as a user with the "administrator" role
    And I am on "/node/add/normal_page#edit-group-content"
    And I should see 1 "#edit-field-content-paragraphs-add-more-add-modal-form-area-add-more" elements after a while
    And I click by selector "#edit-field-content-paragraphs-add-more-add-modal-form-area-add-more" via JavaScript
    And I trigger the "mousedown" event on ".ui-dialog [name=field_content_paragraphs_node_reference_add_more]"
    Then I should see HTML content matching "publish time scheduled" via translated text after a while
    And I click checkbox by name attribute value "field_content_paragraphs[0][subform][field_node_reference_nodes][0][publish_timer]"
    And I fill in "field_content_paragraphs[0][subform][field_node_reference_nodes][0][start_date][date]" with "11112019"
    And I fill in "field_content_paragraphs[0][subform][field_node_reference_nodes][0][start_date][time]" with "090909AM"
    And I fill in "field_content_paragraphs[0][subform][field_node_reference_nodes][0][end_date][date]" with "11112019"
    And I fill in "field_content_paragraphs[0][subform][field_node_reference_nodes][0][end_date][time]" with "090909AM"
    Then I press the Speichern button
    And I should see HTML content matching "Start date must be before end date" via translated text
    And I should see HTML content matching "End date must be after start date" via translated text

  Scenario: I prove that saved dates are reflected on node edit page
    And I am logged in as a user with the "administrator" role
    And I am on "/node/add/normal_page#edit-group-content"
    And I should see 1 "#edit-field-content-paragraphs-add-more-add-modal-form-area-add-more" elements after a while
    And I click by selector "#edit-field-content-paragraphs-add-more-add-modal-form-area-add-more" via JavaScript
    And I trigger the "mousedown" event on ".ui-dialog [name=field_content_paragraphs_node_reference_add_more]"
    Then I should see HTML content matching "publish time scheduled" via translated text after a while
    And I click checkbox by name attribute value "field_content_paragraphs[0][subform][field_node_reference_nodes][0][publish_timer]"
    And I fill in "field_content_paragraphs[0][subform][field_node_reference_nodes][0][start_date][date]" with "11112050"
    And I fill in "field_content_paragraphs[0][subform][field_node_reference_nodes][0][start_date][time]" with "090909AM"
    And I fill in "field_content_paragraphs[0][subform][field_node_reference_nodes][0][end_date][date]" with "12112052"
    And I fill in "field_content_paragraphs[0][subform][field_node_reference_nodes][0][end_date][time]" with "090909AM"
    And I fill in "title[0][value]" with "Entity date widget test"
    And I fill in "field_content_paragraphs[0][subform][field_node_reference_nodes][0][target_id]" with "Page with text paragraph (18)"
    And I select by label "Teaser schmal" via name attribute value "field_content_paragraphs[0][subform][field_node_reference_viewmode]"
    Then I press the Speichern button
    And I should see HTML content matching "alert-success"
    Then I open node edit form by node title "Entity date widget test" and vertical tab id "edit-group-content"
    And I scroll to top
    And I click by CSS id "field-content-paragraphs-0-edit--2"
    Then I should see HTML content matching "publish time scheduled" via translated text after a while
    And I assert date field with name attribute value "field_content_paragraphs[0][subform][field_node_reference_nodes][0][start_date][date]" contains the date value "2050-11-11"
    And I assert date field with name attribute value "field_content_paragraphs[0][subform][field_node_reference_nodes][0][end_date][date]" contains the date value "2052-12-11"

  Scenario: I prove that time cannot be inserted without a date
    And I am logged in as a user with the "administrator" role
    And I am on "/node/add/normal_page#edit-group-content"
    And I should see 1 "#edit-field-content-paragraphs-add-more-add-modal-form-area-add-more" elements after a while
    And I click by selector "#edit-field-content-paragraphs-add-more-add-modal-form-area-add-more" via JavaScript
    And I trigger the "mousedown" event on ".ui-dialog [name=field_content_paragraphs_node_reference_add_more]"
    Then I should see HTML content matching "publish time scheduled" via translated text after a while
    And I click checkbox by name attribute value "field_content_paragraphs[0][subform][field_node_reference_nodes][0][publish_timer]"
    And I fill in "field_content_paragraphs[0][subform][field_node_reference_nodes][0][start_date][date]" with ""
    And I fill in "field_content_paragraphs[0][subform][field_node_reference_nodes][0][start_date][time]" with "090909AM"
    And I fill in "field_content_paragraphs[0][subform][field_node_reference_nodes][0][end_date][date]" with ""
    And I fill in "field_content_paragraphs[0][subform][field_node_reference_nodes][0][end_date][time]" with "090909AM"
    And I fill in "title[0][value]" with "Entity date widget test"
    And I fill in "field_content_paragraphs[0][subform][field_node_reference_nodes][0][target_id]" with "Page with text paragraph (18)"
    And I select by label "Teaser schmal" via name attribute value "field_content_paragraphs[0][subform][field_node_reference_viewmode]"
    Then I press the Speichern button
    And I should see HTML content matching "messages--error"

  Scenario: I prove that I can insert a start date only
    And I am logged in as a user with the "administrator" role
    And I am on "/node/add/normal_page#edit-group-content"
    And I should see 1 "#edit-field-content-paragraphs-add-more-add-modal-form-area-add-more" elements after a while
    And I click by selector "#edit-field-content-paragraphs-add-more-add-modal-form-area-add-more" via JavaScript
    And I trigger the "mousedown" event on ".ui-dialog [name=field_content_paragraphs_node_reference_add_more]"
    Then I should see HTML content matching "publish time scheduled" via translated text after a while
    And I click checkbox by name attribute value "field_content_paragraphs[0][subform][field_node_reference_nodes][0][publish_timer]"
    And I fill in "field_content_paragraphs[0][subform][field_node_reference_nodes][0][start_date][date]" with "11112050"
    And I fill in "field_content_paragraphs[0][subform][field_node_reference_nodes][0][start_date][time]" with "090909AM"
    And I fill in "title[0][value]" with "Entity date widget test"
    And I fill in "field_content_paragraphs[0][subform][field_node_reference_nodes][0][target_id]" with "Page with text paragraph (18)"
    And I select by label "Teaser schmal" via name attribute value "field_content_paragraphs[0][subform][field_node_reference_viewmode]"
    Then I press the Speichern button
    And I should see HTML content matching "alert-success" after a while
    Then I open node edit form by node title "Entity date widget test" and vertical tab id "edit-group-content"
    And I scroll to top
    And I click by CSS id "field-content-paragraphs-0-edit--2"
    Then I should see HTML content matching "publish time scheduled" via translated text after a while
    And I assert date field with name attribute value "field_content_paragraphs[0][subform][field_node_reference_nodes][0][start_date][date]" contains the date value "2050-11-11"
    And I assert date field with name attribute value "field_content_paragraphs[0][subform][field_node_reference_nodes][0][end_date][date]" contains the date value ""

  Scenario: I prove that a scheduled teaser is hidden before its start date and visible afterwards
    And I am logged in as a user with the "administrator" role
    And I am on "/node/add/normal_page#edit-group-content"
    And I should see 1 "#edit-field-content-paragraphs-add-more-add-modal-form-area-add-more" elements after a while
    And I click by selector "#edit-field-content-paragraphs-add-more-add-modal-form-area-add-more" via JavaScript
    And I trigger the "mousedown" event on ".ui-dialog [name=field_content_paragraphs_node_reference_add_more]"
    Then I should see HTML content matching "publish time scheduled" via translated text after a while
    And I click checkbox by name attribute value "field_content_paragraphs[0][subform][field_node_reference_nodes][0][publish_timer]"
    And I fill in "field_content_paragraphs[0][subform][field_node_reference_nodes][0][start_date][date]" with date "now + 10 seconds" formatted "mdY"
    And I fill in "field_content_paragraphs[0][subform][field_node_reference_nodes][0][start_date][time]" with date "now + 10 seconds" formatted "hisA"
    And I fill in "title[0][value]" with "Page with a hidden teaser"
    And I fill in "field_content_paragraphs[0][subform][field_node_reference_nodes][0][target_id]" with "Page with all teasers (1)"
    And I select by label "Teaser schmal" via name attribute value "field_content_paragraphs[0][subform][field_node_reference_viewmode]"
    Then I press the Speichern button
    And I should see HTML content matching "alert-success" after a while
    Then I should see 0 ".normal-page.slim" elements
    And I should see 0 ".paragraph-node-reference" elements
    Then I wait 10 seconds
    And I trigger the PHP function "entity_reference_timer_cron"
    And I reload the page
    Then I should see 1 ".normal-page.slim" elements
    And I should see 1 ".paragraph-node-reference" elements

  Scenario: I prove that a scheduled teaser is hidden after its end date
    And I am logged in as a user with the "administrator" role
    And I am on "/node/add/normal_page#edit-group-content"
    And I should see 1 "#edit-field-content-paragraphs-add-more-add-modal-form-area-add-more" elements after a while
    And I click by selector "#edit-field-content-paragraphs-add-more-add-modal-form-area-add-more" via JavaScript
    And I trigger the "mousedown" event on ".ui-dialog [name=field_content_paragraphs_node_reference_add_more]"
    Then I should see HTML content matching "publish time scheduled" via translated text after a while
    And I click checkbox by name attribute value "field_content_paragraphs[0][subform][field_node_reference_nodes][0][publish_timer]"
    And I fill in "field_content_paragraphs[0][subform][field_node_reference_nodes][0][start_date][date]" with date "now - 1 minute" formatted "mdY"
    And I fill in "field_content_paragraphs[0][subform][field_node_reference_nodes][0][start_date][time]" with date "now - 1 minute" formatted "hisA"
    And I fill in "field_content_paragraphs[0][subform][field_node_reference_nodes][0][end_date][date]" with date "now + 10 seconds" formatted "mdY"
    And I fill in "field_content_paragraphs[0][subform][field_node_reference_nodes][0][end_date][time]" with date "now + 10 seconds" formatted "hisA"
    And I fill in "title[0][value]" with "Page with a disappearing teaser"
    And I fill in "field_content_paragraphs[0][subform][field_node_reference_nodes][0][target_id]" with "Page with all teasers (1)"
    And I select by label "Teaser schmal" via name attribute value "field_content_paragraphs[0][subform][field_node_reference_viewmode]"
    Then I press the Speichern button
    And I should see HTML content matching "alert-success" after a while
    Then I should see 1 ".normal-page.slim" elements
    And I should see 1 ".paragraph-node-reference" elements
    Then I wait 10 seconds
    And I trigger the PHP function "entity_reference_timer_cron"
    And I reload the page
    Then I should see 0 ".normal-page.slim" elements
    And I should see 0 ".paragraph-node-reference" elements
