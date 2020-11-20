<?php

namespace Drupal\ui_patterns_settings\Plugin\UIPatterns\SettingType;

use Drupal\ui_patterns_settings\Definition\PatternDefinitionSetting;
use Drupal\ui_patterns_settings\Plugin\PatternSettingTypeBase;

/**
 * Radios setting type.
 *
 * @UiPatternsSettingType(
 *   id = "radios",
 *   label = @Translation("Radios")
 * )
 */
class RadiosSettingType extends PatternSettingTypeBase {

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, $value, PatternDefinitionSetting $def, $form_type) {
    if ($def->getRequired() == FALSE) {
      $options = ["" => $this->t("Please select")];
    }
    else {
      $options = [];
    }

    $options += $def->getOptions();
    $form[$def->getName()] = [
      '#type' => 'radios',
      '#title' => $def->getLabel(),
      '#description' => $def->getDescription(),
      '#default_value' => $this->getValue($value),
      '#options' => $options,
    ];
    $this->handleInput($form[$def->getName()], $def, $form_type);
    return $form;
  }

}
