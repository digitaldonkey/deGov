<?php

namespace Drupal\entity_reference_timer\Plugin\Field\FieldType;

use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\Field\Plugin\Field\FieldType\EntityReferenceItem;
use Drupal\Core\TypedData\DataDefinition;

/**
 * Class EntityReferenceDate.
 *
 * @FieldType(
 *   id = "entity_reference_date",
 *   label = @Translation("Entity reference date"),
 *   description = @Translation("An entity field containing an entity reference with a date."),
 *   category = @Translation("Timed reference"),
 *   default_widget = "entity_reference_autocomplete_date",
 *   default_formatter = "entity_reference_label",
 *   list_class = "\Drupal\Core\Field\EntityReferenceFieldItemList",
 * )
 */
class EntityReferenceDate extends EntityReferenceItem {

  /**
   * {@inheritdoc}
   */
  public static function defaultStorageSettings() {
    return [
      'datetime_type' => 'datetime',
    ] + parent::defaultStorageSettings();
  }

  /**
   * {@inheritdoc}
   */
  public static function propertyDefinitions(FieldStorageDefinitionInterface $field_definition) {
    $properties = parent::propertyDefinitions($field_definition);

    $properties['start_date'] = DataDefinition::create('any')
      ->setLabel(t('The start datetime'))
      ->setDescription(t('A DateTime object.'));

    $properties['end_date'] = DataDefinition::create('any')
      ->setLabel(t('The end datetime'))
      ->setDescription(t('A DateTime object.'));

    return $properties;
  }

  /**
   * {@inheritdoc}
   */
  public static function schema(FieldStorageDefinitionInterface $field_definition) {
    $schema = parent::schema($field_definition);
    $schema['columns']['start_date'] = [
      'description' => 'The date value.',
      'type'        => 'varchar',
      'length'      => 50,
    ];
    $schema['columns']['end_date'] = [
      'description' => 'The date value.',
      'type'        => 'varchar',
      'length'      => 50,
    ];

    return $schema;
  }

}
