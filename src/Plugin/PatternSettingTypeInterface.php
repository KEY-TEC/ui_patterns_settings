<?php

namespace Drupal\ui_patterns_settings\Plugin;

use Drupal\Component\Plugin\ConfigurablePluginInterface;
use Drupal\ui_patterns_settings\Definition\PatternDefinitionSetting;

/**
 * Defines an interface for UI Patterns setting type plugins.
 */
interface PatternSettingTypeInterface extends ConfigurablePluginInterface {

  /**
   * Returns the configuration form elements specific to this settings plugin..
   *
   * @param array $form
   *   The form definition array for the settings configuration form.
   * @param string $value
   *   The stored default value.
   * @param \Drupal\ui_patterns_settings\Definition\PatternDefinitionSetting $def
   *   The pattern definition.
   *
   * @return array
   *   The configuration form.
   */
  public function settingsForm(array $form, $value, PatternDefinitionSetting $def);

  /**
   * Preprocess setting variable.
   *
   * @param string $value
   *   The stored value.
   * @param array $context
   *   Context informations.
   *   Keys:
   *    - entity.
   * @param \Drupal\ui_patterns_settings\Definition\PatternDefinitionSetting $def
   *   The pattern definition.
   *
   * @return string
   *   The processed value.
   */
  public function settingsPreprocess($value, array $context, PatternDefinitionSetting $def);

  /**
   * Returns the processed setting variable.
   *
   * @param string $value
   *   The stored value.
   * @param array $context
   *   Context informations.
   *
   * @return mixed
   *   The processed value.
   */
  public function preprocess($value, array $context);

  /**
   * Returns the settings configuration form.
   *
   * @param array $form
   *   The form definition array for the settings configuration form.
   * @param string $value
   *   The stored default value.
   */
  public function buildConfigurationForm(array $form, $value);

}
