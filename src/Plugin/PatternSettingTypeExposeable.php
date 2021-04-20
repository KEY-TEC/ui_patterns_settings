<?php

namespace Drupal\ui_patterns_settings\Plugin;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\ui_patterns_settings\Definition\PatternDefinitionSetting;

/**
 * Interface for setting types with exposed fields .
 */
interface PatternSettingTypeExposeable {

  const EXPOSED_FIELD_PREFIX  = 'uips';

  /**
   * Returns the exposed field.
   * @param \Drupal\ui_patterns_settings\Definition\PatternDefinitionSetting $def
   *
   * @return \Drupal\Core\Field\BaseFieldDefinition
   *   The base field definition.
   */
  public function getExposeField(PatternDefinitionSetting $def);

  public function getExposeStorage(PatternDefinitionSetting $def);

  public function processExpose(FieldItemListInterface $field, array $context);
}
