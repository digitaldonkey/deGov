<?php

namespace Drupal\media_file_links\Service;

use Drupal\Core\Cache\Cache;
use Drupal\Core\Entity\Entity;
use Drupal\Core\Entity\EntityInterface;
use Drupal\media\Entity\Media;
use Drupal\media\MediaInterface;
use Drupal\menu_link_content\Entity\MenuLinkContent;
use Drupal\menu_link_content\MenuLinkContentInterface;
use Drupal\node\Entity\Node;
use Drupal\node\NodeInterface;
use Drupal\paragraphs\Entity\Paragraph;
use Drupal\paragraphs\ParagraphInterface;

/**
 * Class MediaFileLinkUsageTracker.
 *
 * @package Drupal\media_file_links\Service
 */
class MediaFileLinkUsageTracker {

  private $placeholderHandler;

  public function __construct(MediaFileLinkPlaceholderHandler $placeholder_handler) {
    $this->placeholderHandler = $placeholder_handler;
  }

  public function trackMediaUsage(EntityInterface $entity): void {
    $this->deletePriorUsages($entity);

    if ($entity instanceof NodeInterface) {
      $this->trackMediaUsageInNode($entity);
    }

    if ($entity instanceof MenuLinkContentInterface) {
      $this->trackMediaUsageInMenuLinkContent($entity);
    }

    if ($entity instanceof ParagraphInterface) {
      $this->trackMediaUsageInParagraph($entity);
    }

    if ($entity instanceof MediaInterface) {
      $this->trackMediaUsageInMedia($entity);

      // This is kind of a stupid workaround, actively watching the referenced entity for updates, then invalidating the referencing entities' caches.
      // If this issue https://www.drupal.org/project/drupal/issues/2537588 is ever merged, we might be able to use cache tags to achieve this more efficiently.
      $mediaUsages = $this->getUsagesByMediaIds([$entity->id()]);

      $menuCacheCleared = FALSE;
      foreach($mediaUsages as $mediaUsage) {
        if(!$menuCacheCleared && $mediaUsage['referencing_entity_type'] === 'menu_link_content') {
          \Drupal::service('plugin.manager.menu.link')->rebuild();
          $menuCacheCleared = TRUE;
        }

        if(\in_array($mediaUsage['referencing_entity_type'], ['media', 'node', 'paragraph'])) {
          \Drupal::service('cache_tags.invalidator')->invalidateTags([$mediaUsage['referencing_entity_type'] . ':' . $mediaUsage['referencing_entity_id']]);
        }
      }
    }
  }

  private function trackMediaUsageInNode(NodeInterface $node): void {
    foreach ($node->getFields() as $field) {
      $fieldValue = $field->getString();
      if ($this->placeholderHandler->isValidMediaFileLinkPlaceholder($fieldValue)) {
        $this->storeUsage($node->id(), 'node', $field->getName(), $node->get('langcode')
          ->getString(), $this->placeholderHandler->getMediaIdFromPlaceholder($fieldValue));
      }
    }
  }

  private function trackMediaUsageInMenuLinkContent(MenuLinkContentInterface $menuLinkContent): void {
    $linkValue = $menuLinkContent->get('link')->getValue();
    if (!empty($linkValue[0]['uri']) && $this->placeholderHandler->isValidMediaFileLinkPlaceholder($linkValue[0]['uri'])) {
      $this->storeUsage($menuLinkContent->id(), 'menu_link_content', 'link', $menuLinkContent->get('langcode')
        ->getString(), $this->placeholderHandler->getMediaIdFromPlaceholder($linkValue[0]['uri']));
    }
  }

  private function trackMediaUsageInParagraph(ParagraphInterface $paragraph): void {
    foreach ($paragraph->getFields() as $field) {
      $fieldValue = $field->getString();
      if ($this->placeholderHandler->isValidMediaFileLinkPlaceholder($fieldValue)) {
        $this->storeUsage($paragraph->id(), 'paragraph', $field->getName(), $paragraph->get('langcode')
          ->getString(), $this->placeholderHandler->getMediaIdFromPlaceholder($fieldValue));
      }
    }
  }

  private function trackMediaUsageInMedia(MediaInterface $media): void {
    foreach ($media->getFields() as $field) {
      $fieldValue = $field->getString();
      if ($this->placeholderHandler->isValidMediaFileLinkPlaceholder($fieldValue)) {
        $this->storeUsage($media->id(), 'media', $field->getName(), $media->get('langcode')
          ->getString(), $this->placeholderHandler->getMediaIdFromPlaceholder($fieldValue));
      }
    }
  }

  private function storeUsage(int $referencingEntityId, string $referencingEntityType, string $referencingEntityField, string $referencingEntityLangcode, int $mediaEntityId): void {
    \Drupal::database()
      ->insert('media_file_links_usage')
      ->fields([
        'referencing_entity_id'       => $referencingEntityId,
        'referencing_entity_type'     => $referencingEntityType,
        'referencing_entity_field'    => $referencingEntityField,
        'referencing_entity_langcode' => $referencingEntityLangcode,
        'media_entity_id'             => $mediaEntityId,
      ])
      ->execute();
  }

  public function deletePriorUsages(EntityInterface $entity): void {
    switch(TRUE) {
      case $entity instanceof NodeInterface:
      case $entity instanceof MenuLinkContentInterface:
      case $entity instanceof ParagraphInterface:
      case $entity instanceof MediaInterface:
        $deleteQuery = \Drupal::database()
          ->delete('media_file_links_usage')
          ->condition('referencing_entity_id', $entity->id())
          ->condition('referencing_entity_langcode', $entity->get('langcode')->getString());
    }

    switch(TRUE) {
      case $entity instanceof NodeInterface:
        $deleteQuery
          ->condition('referencing_entity_type', 'node');
        break;
      case $entity instanceof MenuLinkContentInterface:
        $deleteQuery
          ->condition('referencing_entity_type', 'menu_link_content');
        break;
      case $entity instanceof ParagraphInterface:
        $deleteQuery
          ->condition('referencing_entity_type', 'paragraph');
        break;
      case $entity instanceof MediaInterface:
        $deleteQuery
          ->condition('referencing_entity_type', 'media');
        break;
    }

    if(!empty($deleteQuery)) {
      $deleteQuery->execute();
    }
  }

  public function getUsagesByMediaIds(array $mediaIds, bool $loadFullEntities = TRUE): array {
    $queryResultsStatement = \Drupal::database()
      ->select('media_file_links_usage', 'mflu')
      ->fields('mflu')
      ->condition('media_entity_id', $mediaIds, 'IN')
      ->execute();

    $usages = $queryResultsStatement->fetchAll(\PDO::FETCH_ASSOC);

    if (!empty($usages)) {
      foreach ($usages as $usageKey => &$usage) {

        $referencingEntity = NULL;
        switch ($usage['referencing_entity_type']) {
          case 'media':
            $referencingEntity = Media::load($usage['referencing_entity_id']);
            break;
          case 'node':
            $referencingEntity = Node::load($usage['referencing_entity_id']);
            break;
          case 'paragraph':
            $referencingEntity = Paragraph::load($usage['referencing_entity_id']);
            break;
          case 'menu_link_content':
            $referencingEntity = MenuLinkContent::load($usage['referencing_entity_id']);
            break;
        }

        if($loadFullEntities) {
          $usage['media_entity'] = Media::load($usage['media_entity_id']);
          $usage['referencing_entity'] = $referencingEntity;
          $usage['referencing_entity_field_label'] = $referencingEntity->get($usage['referencing_entity_field'])
            ->getFieldDefinition()
            ->getLabel();
        }
      }
    }

    return $usages;
  }

}
