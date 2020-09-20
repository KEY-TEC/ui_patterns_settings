<?php

namespace Drupal\ui_patterns_settings\Plugin\UIPatterns\SettingType;

use Drupal\ui_patterns_settings\Definition\PatternDefinitionSetting;
use Drupal\ui_patterns_settings\Plugin\PatternSettingTypeBase;

/**
 * Checkbox setting type.
 *
 * @UiPatternsSettingType(
 *   id = "checkbox",
 *   label = @Translation("Checkboxes")
 * )
 */
class CheckboxSettingType extends PatternSettingTypeBase {

  /**
   * {@inheritdoc}
   */
  public function settingsPreprocess($value, array $context, PatternDefinitionSetting $def) {
    $selected_options = [];
    $defined_options = $def->getOptions();
    if (is_array($value)) {
      foreach ($value as $checkbox_key => $checkbox_value) {
        if ($checkbox_value != "0") {
          $selected_options[$checkbox_key] = isset($defined_options[$checkbox_value]) ? $defined_options[$checkbox_value] : $checkbox_value;
        }
      }
    }
    return $selected_options;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, $value, PatternDefinitionSetting $def) {
    $def = $this->getPatternSettingDefinition();
    $value = $this->getValue($value);
    if (is_scalar($value)) {
      $value = [$value];
    }
    if (empty($value)) {
      $value = [];
    }
    $form[$def->getName()] = [
      '#type' => 'checkboxes',
      '#title' => $def->getLabel(),
      '#description' => $def->getDescription(),
      '#default_value' => $value,
      '#options' => $def->getOptions(),
    ];
    $this->handleInput($form[$def->getName()], $def);
    return $form;
  }

}
