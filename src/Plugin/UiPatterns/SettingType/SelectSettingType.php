<?php

namespace Drupal\ui_patterns_settings\Plugin\UIPatterns\SettingType;

use Drupal\ui_patterns_settings\Definition\PatternDefinitionSetting;
use Drupal\ui_patterns_settings\Plugin\PatternSettingTypeBase;

/**
 * Select setting type.
 *
 * @UiPatternsSettingType(
 *   id = "select",
 *   label = @Translation("Select")
 * )
 */
class SelectSettingType extends PatternSettingTypeBase {

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, $value, PatternDefinitionSetting $def) {
    $options = ["" => $this->t("Please select")];
    $options += $def->getOptions();
    $form[$def->getName()] = [
      '#type' => 'select',
      '#title' => $def->getLabel(),
      '#description' => $def->getDescription(),
      '#default_value' => $this->getValue($value),
      '#required' => $def->getRequired(),
      '#options' => $options,
    ];
    return $form;
  }

}
