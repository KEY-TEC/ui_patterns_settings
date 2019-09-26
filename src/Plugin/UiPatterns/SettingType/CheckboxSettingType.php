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
  public function settingsForm(array $form, $value, PatternDefinitionSetting $def) {
    $def = $this->getPatternSettingDefinition();
    $value = $this->getValue($value);
    if (is_scalar($value)) {
      $value = [$value];
    }
    $form[$def->getName()] = [
      '#type' => 'checkboxes',
      '#title' => $def->getLabel(),
      '#description' => $def->getDescription(),
      '#default_value' => $value,
      '#required' => $def->getRequired(),
      '#options' => $def->getOptions(),
    ];
    return $form;
  }

}
