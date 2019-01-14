@api @drupal
Feature: deGov - Media types

  Background:
    Given I proof that the following Drupal modules are installed:
      | degov_paragraph_media_reference |
      | degov_media_video_mobile        |

  Scenario: Checking available media types
    Given I am logged in as a user with the "administrator" role
    And I am on "/admin/structure/media"
    Then I should see text matching "Adresse"
    And I should see text matching "Audio"
    And I should see text matching "Bild"
    And I should see text matching "Bildergalerie"
    And I should see text matching "Dokument"
    And I should see text matching "Instagram"
    And I should see text matching "Kontakt"
    And I should see text matching "Person"
    And I should see text matching "Tweet"
    And I should see text matching "Video"
    And I should see text matching "Video Upload"
    And I should see text matching "Responsives Video"
    And I should see text matching "Zitat"

  Scenario: Media type address has all required fields
    Given I am logged in as a user with the "administrator" role
    And I am on "/admin/structure/media/manage/address/fields"
    Then I should see text matching "field_address_address"
    And I should see text matching "field_address_email"
    And I should see text matching "field_address_fax"
    And I should see text matching "field_media_generic"
    And I should see text matching "field_address_location"
    And I should see text matching "field_include_search"
    And I should see text matching "field_media_in_library"
    And I should see text matching "field_tags"
    And I should see text matching "field_address_phone"
    And I should see text matching "field_address_title"
    And I should see text matching "field_title"

  Scenario: Media type audio has all required fields
    Given I am logged in as a user with the "administrator" role
    And I am on "/admin/structure/media/manage/audio/fields"
    Then I should see text matching "field_audio_caption"
    And I should see text matching "field_description"
    And I should see text matching "field_copyright"
    And I should see text matching "field_media_generic_1"
    And I should see text matching "field_allow_download"
    And I should see text matching "field_media_duration"
    And I should see text matching "field_include_search"
    And I should see text matching "field_audio_mp3"
    And I should see text matching "field_audio_ogg"
    And I should see text matching "field_media_in_library"
    And I should see text matching "field_tags"
    And I should see text matching "field_media_transcription"
    And I should see text matching "field_audio_preview"
    And I should see text matching "field_title"
    And I should see text matching "field_media_publish_date"
    Then I am on "/admin/structure/media/manage/audio/fields/media.audio.field_media_publish_date"
    And the "edit-required" checkbox should be checked

  Scenario: Media type video_mobile has all required fields
    Given I am logged in as a user with the "administrator" role
    Then I am installing the "degov_media_video_mobile" module
    Then I am on "/admin/structure/media/manage/video_mobile/fields"
    Then I should see text matching "field_media_accessibility"
    And I should see text matching "field_description"
    And I should see text matching "field_copyright"
    And I should see text matching "field_fullhd_video_mobile_mp4"
    And I should see text matching "field_media_generic_9"
    And I should see text matching "field_hdready_video_mobile_mp4"
    And I should see text matching "field_allow_download"
    And I should see text matching "field_allow_download_mobile"
    And I should see text matching "field_allow_download_hdready"
    And I should see text matching "field_allow_download_fullhd"
    And I should see text matching "field_allow_download_4k"
    And I should see text matching "field_media_duration"
    And I should see text matching "field_include_search"
    And I should see text matching "field_mobile_video_mobile_mp4"
    And I should see text matching "field_media_in_library"
    And I should see text matching "field_tags"
    And I should see text matching "field_media_language"
    And I should see text matching "field_video_mobile_mp4"
    And I should see text matching "field_media_transcription"
    And I should see text matching "field_ultrahd4k_video_mobile_mp4"
    And I should see text matching "field_video_mobile_subtitle"
    And I should see text matching "field_subtitle"
    And I should see text matching "field_media_publish_date"
    And I should see text matching "field_video_mobile_caption"
    And I should see text matching "field_video_mobile_preview"
    And I should see text matching "field_title"
    Then I am on "/admin/structure/media/manage/video_mobile/fields/media.video_mobile.field_video_mobile_mp4"
    And the "edit-required" checkbox should be checked

  Scenario: Media type image has all required fields
    Given I am logged in as a user with the "administrator" role
    And I am on "/admin/structure/media/manage/image/fields"
    Then I should see text matching "field_description"
    And I should see text matching "field_image_caption"
    And I should see text matching "field_image_width"
    And I should see text matching "field_copyright"
    And I should see text matching "field_media_publish_date"
    And I should see text matching "field_allow_download"
    And I should see text matching "field_image_height"
    And I should see text matching "image"
    And I should see text matching "field_include_search"
    And I should see text matching "field_image_mime"
    And I should see text matching "field_media_in_library"
    And I should see text matching "field_tags"
    And I should see text matching "field_subtitle"
    And I should see text matching "field_title"
    Then I am on "/admin/structure/media/manage/image/fields/media.image.field_media_publish_date"
    And the "edit-required" checkbox should be checked

  Scenario: I verify that image media entities have copyright related fields
    Given I am logged in as an "Administrator"
    And I have dismissed the cookie banner if necessary
    And I am on "/media/add/image"
    And I choose "Beschreibung" from tab menu
    Then I should see 1 form element with the label "Copyright" and a required input field
    And I should see 1 form element with the label "Bild ist frei" and a "checkbox" field
    And I check checkbox with id "edit-field-royalty-free-value"
    Then I should see 0 form element with the label "Copyright" and a required input field

  Scenario: I verify that image media entities have copyright related fields
    Given I am logged in as an "Administrator"
    And I have dismissed the cookie banner if necessary
    And I am on "/media/add/image"
    And I choose "Beschreibung" from tab menu
    Then I should see 1 form element with the label "Copyright" and a required input field
    And I should see 1 form element with the label "Bild ist frei" and a "checkbox" field
    And I check checkbox with id "edit-field-royalty-free-value"
    Then I should see 0 form element with the label "Copyright" and a required input field

  Scenario: Media type gallery has all required fields
    Given I am logged in as a user with the "administrator" role
    And I am on "/admin/structure/media/manage/gallery/fields"
    Then I should see text matching "field_description"
    And I should see text matching "field_media_generic_5"
    And I should see text matching "field_gallery_images"
    And I should see text matching "field_include_search"
    And I should see text matching "field_tags"
    And I should see text matching "field_gallery_title"
    And I should see text matching "field_media_in_library"
    And I should see text matching "field_title"
    And I should see text matching "field_media_publish_date"
    Then I am on "/admin/structure/media/manage/gallery/fields/media.gallery.field_media_publish_date"
    And the "edit-required" checkbox should be checked

  Scenario: Media type document has all required fields
    Given I am logged in as a user with the "administrator" role
    And I am on "/admin/structure/media/manage/document/fields"
    Then I should see text matching "field_document"
    And I should see text matching "field_include_search"
    And I should see text matching "field_media_in_library"
    And I should see text matching "field_tags"
    And I should see text matching "field_title"

  Scenario: Media type instagram has all required fields
    Given I am logged in as a user with the "administrator" role
    And I am on "/admin/structure/media/manage/instagram/fields"
    Then I should see text matching "embed_code"
    And I should see text matching "field_include_search"
    And I should see text matching "field_tags"
    And I should see text matching "field_media_in_library"
    And I should see text matching "field_title"

  Scenario: Media type contact has all requried fields
    Given I am logged in as a user with the "administrator" role
    And I am on "/admin/structure/media/manage/contact/fields"
    Then I should see text matching "field_contact_email"
    And I should see text matching "field_contact_fax"
    And I should see text matching "field_media_generic_3"
    And I should see text matching "field_contact_image"
    And I should see text matching "field_include_search"
    And I should see text matching "field_contact_title"
    And I should see text matching "field_contact_position"
    And I should see text matching "field_media_in_library"
    And I should see text matching "field_tags"
    And I should see text matching "field_contact_tel"
    And I should see text matching "field_title"

  Scenario: Media Type person has all required fields
    Given I am logged in as a user with the "administrator" role
    And I am on "/admin/structure/media/manage/person/fields"
    Then I should see text matching "field_person_image"
    And I should see text matching "field_media_generic_6"
    And I should see text matching "field_person_info"
    And I should see text matching "field_include_search"
    And I should see text matching "field_media_in_library"
    And I should see text matching "field_tags"
    And I should see text matching "field_title"

  Scenario: Media type tweet has all required fields
    Given I am logged in as a user with the "administrator" role
    And I am on "/admin/structure/media/manage/tweet/fields"
    Then I should see text matching "field_include_search"
    And I should see text matching "field_tags"
    And I should see text matching "embed_code"
    And I should see text matching "field_media_in_library"
    And I should see text matching "field_title"

  Scenario: Media type video has all required fields
    Given I am logged in as a user with the "administrator" role
    And I am on "/admin/structure/media/manage/video/fields"
    Then I should see text matching "field_description"
    And I should see text matching "field_video_caption"
    And I should see text matching "field_copyright"
    And I should see text matching "field_media_duration"
    And I should see text matching "field_include_search"
    And I should see text matching "field_media_in_library"
    And I should see text matching "field_tags"
    And I should see text matching "field_media_transcription"
    And I should see text matching "field_media_video_embed_field"
    And I should see text matching "field_video_preview"
    And I should see text matching "field_title"
    And I should see text matching "field_media_publish_date"
    Then I am on "/admin/structure/media/manage/video/fields/media.video.field_media_publish_date"
    And the "edit-required" checkbox should be checked

   Scenario: Media type video_upload has all required fields
     Given I am logged in as a user with the "administrator" role
     And I am on "/admin/structure/media/manage/video_upload/fields"
     Then I should see text matching "field_description"
     And I should see text matching "field_copyright"
     And I should see text matching "field_media_generic_8"
     And I should see text matching "field_allow_download"
     And I should see text matching "field_media_duration"
     And I should see text matching "field_include_search"
     And I should see text matching "field_video_upload_mp4"
     And I should see text matching "field_video_upload_ogg"
     And I should see text matching "field_media_in_library"
     And I should see text matching "field_tags"
     And I should see text matching "field_media_transcription"
     And I should see text matching "field_video_upload_subtitle"
     And I should see text matching "field_video_upload_caption"
     And I should see text matching "field_video_upload_preview"
     And I should see text matching "field_video_upload_webm"
     And I should see text matching "field_title"
     And I should see text matching "field_media_publish_date"
     Then I am on "/admin/structure/media/manage/video_upload/fields/media.video_upload.field_media_publish_date"
     And the "edit-required" checkbox should be checked

  Scenario: Media type citation has all required fields
    Given I am logged in as a user with the "administrator" role
    And I am on "/admin/structure/media/manage/citation/fields"
    Then I should see text matching "field_citation_date"
    And I should see text matching "field_media_generic_2"
    And I should see text matching "field_citation_image"
    And I should see text matching "field_include_search"
    And I should see text matching "field_media_in_library"
    And I should see text matching "field_tags"
    And I should see text matching "field_citation_text"
    And I should see text matching "field_citation_title"
    And I should see text matching "field_title"

  Scenario: Check that media entity types with publish date field have a default value in said field
    Given I am logged in as a user with the "administrator" role
    And I am on "/media/add/audio"
    Then I should see 2 elements with name matching "field_media_publish_date" and a not empty value
    And I am on "/media/add/image"
    Then I should see 2 elements with name matching "field_media_publish_date" and a not empty value
    And I am on "/media/add/gallery"
    Then I should see 2 elements with name matching "field_media_publish_date" and a not empty value
    And I am on "/media/add/video"
    Then I should see 2 elements with name matching "field_media_publish_date" and a not empty value
    And I am on "/media/add/video_upload"
    Then I should see 2 elements with name matching "field_media_publish_date" and a not empty value

  Scenario: I am visiting the media entity type configuration pages
    Given I am on "/admin/structure/media/manage/address"
    Then I am on "/admin/structure/media/manage/gallery"
    Then I am on "/admin/structure/media/manage/video_upload"
    Then I am on "/admin/structure/media/manage/person"
    Then I am on "/admin/structure/media/manage/audio"

  Scenario: Proof that media reference paragraph type references expected media types
    Given I am logged in as a user with the "administrator" role
    And I am on "/admin/structure/paragraphs_type/media_reference/fields/paragraph.media_reference.field_media_reference_media"
    And I proof Checkbox with id "edit-settings-handler-settings-target-bundles-address" has value "checked"
    And I proof Checkbox with id "edit-settings-handler-settings-target-bundles-audio" has value "checked"
    And I proof Checkbox with id "edit-settings-handler-settings-target-bundles-image" has value "checked"
    And I proof Checkbox with id "edit-settings-handler-settings-target-bundles-instagram" has value "checked"
    And I proof Checkbox with id "edit-settings-handler-settings-target-bundles-contact" has value "checked"
    And I proof Checkbox with id "edit-settings-handler-settings-target-bundles-person" has value "checked"
    And I proof Checkbox with id "edit-settings-handler-settings-target-bundles-video-mobile" has value "checked"
    And I proof Checkbox with id "edit-settings-handler-settings-target-bundles-some-embed" has value "checked"
    And I proof Checkbox with id "edit-settings-handler-settings-target-bundles-tweet" has value "checked"
    And I proof Checkbox with id "edit-settings-handler-settings-target-bundles-video" has value "checked"
    And I proof Checkbox with id "edit-settings-handler-settings-target-bundles-video-file" has value "checked"
    And I proof Checkbox with id "edit-settings-handler-settings-target-bundles-video-upload" has value "checked"
    And I proof Checkbox with id "edit-settings-handler-settings-target-bundles-citation" has value "checked"
