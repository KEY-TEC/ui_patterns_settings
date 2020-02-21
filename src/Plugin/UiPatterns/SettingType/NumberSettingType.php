<?php

namespace Drupal\ui_patterns_settings\Plugin\UIPatterns\SettingType;

use Drupal\ui_patterns_settings\Definition\PatternDefinitionSetting;
use Drupal\ui_patterns_settings\Plugin\PatternSettingTypeBase;

/**
 * Number setting type.
 *
 * @UiPatternsSettingType(
 *   id = "number",
 *   label = @Translation("Number")
 * )
 */
class NumberSettingType extends PatternSettingTypeBase {

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, $value, PatternDefinitionSetting $def) {
    $form[$def->getName()] = [
      '#type' => 'number',
      '#title' => $def->getLabel(),
      '#description' => $def->getDescription(),
      '#default_value' => $this->getValue($value),
      '#required' => $def->getRequired(),
    ];
    return $form;
  }

}
