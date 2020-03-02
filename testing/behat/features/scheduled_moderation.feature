@api @drupal @javascript @access
  Feature: deGov Scheduled moderation

    Scenario: Creating node with scheduled publish - Needs update
      Given I am logged in as a user with the "administrator" role
      And I proof that Drupal module "degov_scheduled_updates" is installed
      And I am on "/node/add/normal_page"
      And I fill in "Creating node with scheduled publish" for "Titel"
      And I select "published" in "edit-field-scheduled-publish-moderation-state"
      And I fill in "field_scheduled_publish[value][date]" with date "now" formatted "mdY"
      And I fill in "field_scheduled_publish[value][time]" with date "+10 seconds" formatted "hisA"
      And I press button with label "Add status update" via translated text
      And I wait for AJAX to finish
      And I select "draft" in "edit-moderation-state-0-state"
      And I press button with label "Save" via translated text
      And I wait 60 seconds
      And I am on "/admin/config/system/cron/jobs"
      And I should see an "tr[data-drupal-selector='edit-entities-scheduled-publish-cron'] img[data-drupal-selector='edit-entities-scheduled-publish-cron-scheduled-behind']" element
      And I proof content with title "Creating node with scheduled publish" has moderation state "draft"
      And I run the cron
      And I am on "/admin/config/system/cron/jobs"
      And I should not see an "tr[data-drupal-selector='edit-entities-scheduled-publish-cron'] img[data-drupal-selector='edit-entities-scheduled-publish-cron-scheduled-behind']" element
      And I should not see an "tr[data-drupal-selector='edit-entities-scheduled-publish-cron'] img[src='/core/misc/icons/e32700/error.svg']" element
      And I am on "/admin/config/system/cron/jobs/logs/scheduled_publish_cron"
      And I should not see HTML content matching "/core/misc/icons/e32700/error.svg"
      And I proof content with title "Creating node with scheduled publish" has moderation state "published"

    Scenario: deGov Creating node with scheduled publish - No update
      Given I am logged in as a user with the "administrator" role
      And I proof that Drupal module "degov_scheduled_updates" is installed
      And I am on "/node/add/normal_page"
      And I fill in "Test" for "Titel"
      And I select "published" in "edit-field-scheduled-publish-moderation-state"
      And I fill in "01012118" for "field_scheduled_publish[value][date]"
      And I fill in "010000AM" for "field_scheduled_publish[value][time]"
      And I press button with label "Add status update" via translated text
      And I wait for AJAX to finish
      And I select "draft" in "edit-moderation-state-0-state"
      And I press button with label "Save" via translated text
      And I wait 60 seconds
      And I am on "/admin/config/system/cron/jobs"
      And I should see an "tr[data-drupal-selector='edit-entities-scheduled-publish-cron'] img[data-drupal-selector='edit-entities-scheduled-publish-cron-scheduled-behind']" element
      And I run the cron
      And I am on "/admin/config/system/cron/jobs"
      And I should not see an "tr[data-drupal-selector='edit-entities-scheduled-publish-cron'] img[src='/core/misc/icons/e32700/error.svg']" element
      And I should not see an "tr[data-drupal-selector='edit-entities-scheduled-publish-cron'] img[data-drupal-selector='edit-entities-scheduled-publish-cron-scheduled-behind']" element
      And I am on "/admin/config/system/cron/jobs/logs/scheduled_publish_cron"
      And I should not see HTML content matching "/core/misc/icons/e32700/error.svg"
      And I proof content with title "Test" has moderation state "draft"
