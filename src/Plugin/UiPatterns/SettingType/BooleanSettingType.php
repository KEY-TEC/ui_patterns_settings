<?php

namespace Drupal\ui_patterns_settings\Plugin\UIPatterns\SettingType;

use Drupal\ui_patterns_settings\Definition\PatternDefinitionSetting;
use Drupal\ui_patterns_settings\Plugin\PatternSettingTypeBase;

/**
 * Checkbox setting type.
 *
 * @UiPatternsSettingType(
 *   id = "boolean",
 *   label = @Translation("true/false")
 * )
 */
class BooleanSettingType extends PatternSettingTypeBase {

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, $value, PatternDefinitionSetting $def) {
    $value = $this->getValue($value);
    $form[$def->getName()] = [
      '#type' => 'select',
      '#title' => $def->getLabel(),
      '#description' => $def->getDescription(),
      '#default_value' => $value,
      '#required' => $def->getRequired(),
      '#options' =>
        [0 => $this->t('false'), 1 => $this->t('true')],
    ];
    return $form;
  }

}
