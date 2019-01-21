@api @drupal
Feature: deGov - Content creation

  Background:
    Given I proof that the following Drupal modules are installed:
      | degov_node_press            |
      | degov_node_event            |
      | degov_node_blog             |
      | degov_node_normal_page      |
      | degov_simplenews_references |

  Scenario: I create a press entity and check that the header section is being displayed as expected
    Given I am logged in as a user with the "administrator" role
    And I am on "/node/add/press"
    And I fill in "Titel" with "Test1234"
    And I fill in Textarea with "Test1234"
    And I fill in "edit-field-press-date-0-value-date" with "01012018"
    And I scroll to bottom
    And I press button with label "Save" via translated text
    Then I should see HTML content matching "01.01.2018" after a while
    And I should see "Test1234" in the ".press__header-paragraphs h2" element
    And I should see "Test1234" in the ".press__header-paragraphs .press__teaser-text" element

  Scenario: I see all form fields in normal_page content type
    Given I am logged in as a user with the "administrator" role
    And I am on "/node/add/normal_page"
    And I should see "Titel"
    And I should see "Interner Titel"
    And I should see "Vorschau Titel"
    And I should see "Vorschau Untertitel"
    And I should see "Vorschau Text"
    And I choose "Allgemein" from tab menu
    And I should see "Schlagworte"
    And I should see "Sprache"
    And I should see "Thema"
    And I should see "Inhaltstyp"
    And I should see "Bereich"
    And I should see "Ansichtssteuerung"
    And I choose "Header" via translation from tab menu
    And I should see "KOPFBEREICH"
    And I should see text matching "Add Paragrap" via translated text
    And I choose "Seitenleiste rechts" from tab menu
    And I should see "Seitenleiste rechts"
    And I should see text matching "Add Paragrap" via translated text
    And I choose "Content" via translation from tab menu
    And I should see "INHALTSBEREICH"
    And I should see text matching "Add Paragrap" via translated text

  Scenario: I see all form fields in blog content type
    Given I am logged in as a user with the "administrator" role
    And I am on "/node/add/blog"
    And I should see "Titel"
    And I should see "Interner Titel"
    And I should see "Untertitel"
    And I should see "Datum"
    And I should see "Vorschau Titel"
    And I should see "Vorschau Untertitel"
    And I should see "Vorschau Text"
    And I choose "Allgemein" from tab menu
    And I should see "Schlagworte"
    And I should see "Social Media Buttons anzeigen"
    And I choose "Header" via translation from tab menu
    And I should see "KOPFBEREICH"
    And I should see text matching "Add Paragrap" via translated text
    And I choose "Seitenleiste rechts" from tab menu
    And I should see "Seitenleiste rechts"
    And I should see text matching "Add Paragrap" via translated text
    And I choose "Content" via translation from tab menu
    And I should see "INHALTSBEREICH"
    And I should see text matching "Add Paragrap" via translated text

  Scenario: I see all form fields in faq content type
    Given I am logged in as a user with the "administrator" role
    And I am on "/node/add/faq"
    And I should see "Titel"
    And I should see "Interner Titel"
    And I choose "Description" via translation from tab menu
    And I should see "Beschreibung"
    And I choose "Content" via translation from tab menu
    And I should see "INHALTSBEREICH"
    And I should see text matching "Add Paragrap" via translated text
    And I should see "VERWANTE FAQ"
    And I choose "Allgemein" from tab menu
    And I should see "Bereich"

  Scenario: I see all form fields in newsletter content type
    Given I am logged in as a user with the "administrator" role
    And I am on "/node/add/simplenews_issue"
    And I should see "Titel"
    And I should see "Textkörper"
    And I should see "Newsletter"

  Scenario: I see all form fields in press content type
    Given I am logged in as a user with the "administrator" role
    And I am on "/node/add/press"
    And I should see "Titel"
    And I should see "Interner Titel"
    And I should see "Datum"
    And I should see "Vorschau Titel"
    And I should see "Vorschau Untertitel"
    And I should see "Vorschau Text"
    And I choose "Allgemein" from tab menu
    And I should see "Schlagworte"
    And I should see "Social Media Buttons anzeigen"
    And I should see "Sprache"
    And I should see "Thema"
    And I should see "Inhaltstyp"
    And I should see "Bereich"
    And I should see "Ansichtssteuerung"
    And I choose "Header" via translation from tab menu
    And I should see "KOPFBEREICH"
    And I should see text matching "Add Paragrap" via translated text
    And I choose "Seitenleiste rechts" from tab menu
    And I should see "Seitenleiste rechts"
    And I should see text matching "Add Paragrap" via translated text
    And I choose "Content" via translation from tab menu
    And I should see "INHALTSBEREICH"
    And I should see text matching "Add Paragrap" via translated text

  Scenario: I see all form fields in event content type
    Given I am logged in as a user with the "administrator" role
    And I am on "/node/add/event"
    And I should see "Titel"
    And I should see "Interner Titel"
    And I should see "DATUM"
    And I should see "ENDDATUM"
    And I should see "Anzeigezeit"
    And I should see "ADRESSE"
    And I should see "Land"
    And I should see "Firma"
    And I should see text matching "Street address" via translated text
    And I should see text matching "Postal code" via translated text
    And I should see text matching "City" via translated text
    And I should see "Vorschau Titel"
    And I should see "Vorschau Untertitel"
    And I should see "Vorschau Text"
    And I choose "Allgemein" from tab menu
    And I should see "Schlagworte"
    And I should see "Social Media Buttons anzeigen"
    And I should see "Sprache"
    And I should see "Thema"
    And I should see "Inhaltstyp"
    And I should see "Bereich"
    And I should see "Ansichtssteuerung"
    And I choose "Header" via translation from tab menu
    And I should see "KOPFBEREICH"
    And I should see text matching "Add Paragrap" via translated text
    And I choose "Seitenleiste rechts" from tab menu
    And I should see "Seitenleiste rechts"
    And I should see text matching "Add Paragrap" via translated text
    And I choose "Content" via translation from tab menu
    And I should see "INHALTSBEREICH"
    And I should see text matching "Add Paragrap" via translated text
