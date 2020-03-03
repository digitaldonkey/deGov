<?php

namespace Drupal\entity_reference_timer;

use Drupal\Core\Entity\Entity\EntityViewDisplay;
use Drupal\Core\Entity\EntityInterface;
use Drupal\field\Entity\FieldConfig;
use Drupal\field\Entity\FieldStorageConfig;

/**
 * Class FieldSwitchService.
 */
class FieldSwitchService {

  /**
   * Field name.
   *
   * @var string
   */
  public static $fieldName = 'field_node_reference_nodes';

  /**
   * Field type.
   *
   * @var string
   */
  public static $fieldType = 'entity_reference_date';

  /**
   * Entity type.
   *
   * @var string
   */
  public static $entityType = 'paragraph';

  /**
   * Bundle.
   *
   * @var string
   */
  public static $bundle = 'node_reference';

  /**
   * Display.
   *
   * @var string
   */
  private static $display = 'entity_reference_date_display_default';

  /**
   * Update field.
   */
  public static function updateField(): void {
    if (!self::fieldExists()) {
      return;
    }
    self::switchFieldStorageAndMigrateContent();
    self::switchFieldWidget();
    self::switchFormDisplay();
    self::switchFieldDisplay();
  }

  /**
   * Switch field display.
   */
  private static function switchFieldDisplay(): void {
    /**
     * @var \Drupal\Core\Entity\Entity\EntityViewDisplayInterface $display
     */
    $display = EntityViewDisplay::load(self::$entityType . '.' . self::$bundle . '.' . 'default');

    $display->setComponent(self::$fieldName, [
      'weight'               => 3,
      'label'                => 'hidden',
      'third_party_settings' => [],
      'type'                 => self::$display,
      'region'               => 'content',
    ])->save();
  }

  /**
   * Uninstall field.
   */
  public static function uninstallField(): void {
    if (!self::fieldExists()) {
      return;
    }
    self::$fieldType = 'entity_reference';
    self::$display = 'entity_reference_display_default';
    self::updateField();
  }

  /**
   * Check if the field config we expect is present.
   *
   * @return bool
   *   Does the config exist?
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public static function fieldExists(): bool {
    $fields = \Drupal::entityTypeManager()->getStorage('field_config')
      ->loadByProperties(['field_name' => self::$fieldName]);
    return !empty($fields[self::getFieldConfigKey()]) && $fields[self::getFieldConfigKey()] instanceof EntityInterface;
  }

  /**
   * Assemble and return the key of the field config we are going to work with.
   */
  public static function getFieldConfigKey(): string {
    return self::$entityType . '.' . self::$bundle . '.' . self::$fieldName;
  }

  /**
   * Switch form display.
   */
  private static function switchFormDisplay(): void {
    /**
     * @var \Drupal\Core\Entity\Display\EntityDisplayInterface $formDisplay
     */
    $formDisplay = \Drupal::entityTypeManager()
      ->getStorage('entity_form_display')
      ->load(self::$entityType . '.' . self::$bundle . '.default');

    $formDisplay->setComponent(self::$fieldName, ['weight' => 10])->save();
  }

  /**
   * Switch field widget.
   */
  private static function switchFieldWidget(): void {
    $fields = \Drupal::entityTypeManager()->getStorage('field_config')
      ->loadByProperties(['field_name' => self::$fieldName]);

    $field = array_shift($fields);

    $newField = $field->toArray();
    $newField['field_type'] = self::$fieldType;

    $newField = FieldConfig::create($newField);
    $newField->original = $field;
    $newField->enforceIsNew(FALSE);
    $newField->save();
  }

  /**
   * Switch field storage and migrate content.
   */
  private static function switchFieldStorageAndMigrateContent(): void {
    $database = \Drupal::database();
    $table = 'paragraph__field_node_reference_nodes';

    $fieldStorage = FieldStorageConfig::loadByName(self::$entityType, self::$fieldName);

    if ($fieldStorage === NULL) {
      return;
    }

    $rows = NULL;

    if ($database->schema()->tableExists($table)) {
      $rows = $database->select($table, 'p')
        ->fields('p')
        ->execute()
        ->fetchAll();
    }

    $newFields = [];

    foreach ($fieldStorage->getBundles() as $bundle => $label) {
      $field = FieldConfig::loadByName(self::$entityType, $bundle, self::$fieldName);
      $newField = $field->toArray();
      $newField['type'] = self::$fieldType;
      $newFields[] = $newField;
    }

    $newFieldStorage = $fieldStorage->toArray();
    $newFieldStorage['type'] = self::$fieldType;

    $fieldStorage->delete();

    field_purge_batch(10);

    $newFieldStorage = FieldStorageConfig::create($newFieldStorage);
    $newFieldStorage->save();

    foreach ($newFields as $newField) {
      $newField = FieldConfig::create($newField);
      $newField->save();
    }

    if ($rows !== NULL) {
      foreach ($rows as $row) {

        if (self::$fieldType === 'entity_reference') {
          unset($row->field_node_reference_nodes_start_date, $row->field_node_reference_nodes_end_date);
        }

        $database->insert($table)
          ->fields((array) $row)
          ->execute();
      }
    }
  }

}
