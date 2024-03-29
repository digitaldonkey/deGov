<?php
/**
 * @file MediaGallery.php.
 */

namespace Drupal\degov_theme\Preprocess;

/**
 * Class MediaGallery
 *
 * @package Drupal\degov_theme\Preprocess
 */
class MediaGallery {

  /**
   * Preprocess media for search.
   *
   * @param array $variables
   */
  static public function preprocess(array &$variables) {
    /** @var \Drupal\media\Entity\Media $media */

    $media = $variables['media'];
    if ($media->hasField('field_gallery_images')) {
      $media_images = $media->get('field_gallery_images')->referencedEntities();
      if ($media_images) {
        foreach ($media_images as $media_image) {
          if($media_image->image->entity instanceof \Drupal\Core\Entity\EntityInterface) {
            $variables['gallery_images'][] = [
              '#theme' => 'image_style',
              '#style_name' => 'teaser_squared_1_1_320',
              '#uri' => $media_image->image->entity->getFileUri(),
            ];
          }
        }
      }
    }

  }

}
