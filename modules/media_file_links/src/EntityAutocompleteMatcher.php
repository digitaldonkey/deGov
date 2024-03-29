<?php

namespace Drupal\media_file_links;

use Drupal\Component\Utility\Html;
use Drupal\Component\Utility\Tags;

/**
 * Class EntityAutocompleteMatcher.
 *
 * @package Drupal\media_file_links
 */
class EntityAutocompleteMatcher extends \Drupal\Core\Entity\EntityAutocompleteMatcher {

  /**
   * Gets matched labels based on a given search string.
   */
  public function getMatches($target_type, $selection_handler, $selection_settings, $string = '') {

    $matches = [];

    $options = [
      'target_type'      => $target_type,
      'handler'          => $selection_handler,
      'handler_settings' => $selection_settings,
    ];

    $handler = $this->selectionManager->getInstance($options);

    // See: https://www.chapterthree.com/blog/how-alter-entity-autocomplete-results-drupal-8
    // Search defined entities.
    if ($string !== NULL) {
      // Get an array of matching entities.
      $match_operator = !empty($selection_settings['match_operator']) ? $selection_settings['match_operator'] : 'CONTAINS';
      $entity_labels = $handler->getReferenceableEntities($string, $match_operator, 10);

      // Loop through the entities and convert them into autocomplete output.
      foreach ($entity_labels as $values) {
        foreach ($values as $entity_id => $label) {
          $entity = \Drupal::entityTypeManager()
            ->getStorage($target_type)
            ->load($entity_id);
          $entity = \Drupal::entityManager()
            ->getTranslationFromContext($entity);

          $type = !empty($entity->type->entity) ? $entity->type->entity->label() : $entity->bundle();
          $status = '';

          $key = $label . ' (' . $entity_id . ')';
          // Strip things like starting/trailing white spaces, line breaks and tags.
          $key = preg_replace('/\s\s+/', ' ', str_replace("\n", '', trim(Html::decodeEntities(strip_tags($key)))));
          // Names containing commas or quotes must be wrapped in quotes.
          $key = Tags::encode($key);
          $label = $label . ' (' . $entity_id . ') [' . $type . $status . ']';
          $matches[] = ['value' => $key, 'label' => $label];
        }
      }
    }

    // If previous search was not for Media, run a search on linkable Media now.
    if (!\in_array($target_type, ['media', 'user', 'taxonomy_term'])) {
      $mediaResults = \Drupal::service('media_file_links.file_suggester')
        ->findBySearchString($string, FALSE);

      if (!empty($mediaResults)) {
        foreach ($mediaResults as $mediaEntity) {
          $key = sprintf(
            '%s [%s, %s] <media/file/%s>',
            $mediaEntity['title'],
            $mediaEntity['bundleLabel'],
            $mediaEntity['filename'],
            $mediaEntity['id']
          );
          $label = '<i class="' . $mediaEntity['iconClass'] . '" /> ' . $key;
          $matches[] = ['value' => $key, 'label' => $label];
        }
      }
    }

    return $matches;
  }

}
