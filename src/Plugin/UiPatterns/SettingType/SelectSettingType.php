<?php

namespace Drupal\ui_patterns_settings\Plugin\UIPatterns\SettingType;

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
  public function buildConfigurationForm(array $form, $value) {
    $def = $this->getPatternSettingDefinition();
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
