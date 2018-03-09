<?php

/**
 * Migrate field_gallery_title to field_title.
 */
function degov_media_gallery_post_update_migrate_field_title(&$sandbox) {
  // Initialize some variables during the first pass through.
  if (!isset($sandbox['total'])) {
    $sandbox['is_index'] = FALSE;
    $max = \Drupal::entityQuery('media')
      ->condition('bundle', 'gallery')
      ->count()
      ->execute();
    $sandbox['total'] = $max;
    $sandbox['current'] = 0;
    if (\Drupal::moduleHandler()->moduleExists('degov_search_media')) {
      $index = \Drupal\search_api\Entity\Index::load('search_media');
      if ($index) {
        $index->setOption('index_directly', FALSE);
        $index->save();
        $sandbox['is_index'] = TRUE;
      }
    }
  }

  $media_per_batch = 50;

  // Handle one pass through.
  $mids = \Drupal::entityQuery('media')
    ->condition('bundle', 'gallery')
    ->range($sandbox['current'], $sandbox['current'] + $media_per_batch)
    ->execute();
  $medias = \Drupal\media\Entity\Media::loadMultiple($mids);
  foreach($medias as $media) {
    if ($media->get('field_gallery_title')->isEmpty()) {
      $sandbox['current']++;
      continue;
    }
    $caption = $media->get('field_gallery_title')->getValue();
    $media->set('field_title', $caption);
    $media->save();
    $sandbox['current']++;
  }

  $sandbox['#finished'] = ($sandbox['current'] / $sandbox['total']);
  if ($sandbox['#finished'] == 1 && $sandbox['is_index']) {
    $index = \Drupal\search_api\Entity\Index::load('search_media');
    $index->setOption('index_directly', TRUE);
    $index->save();
  }
  return t($sandbox['current'] . ' media processed.');
}
