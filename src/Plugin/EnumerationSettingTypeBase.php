<?php

namespace Drupal\ui_patterns_settings\Plugin;

use Drupal\ui_patterns_settings\Definition\PatternDefinitionSetting;

/**
 * Base class for enumerations like radios or select.
 */
abstract class EnumerationSettingTypeBase extends PatternSettingTypeBase {

  /**
   * Returns empty option.
   *
   * @return array
   *   The empty option.
   */
  protected function emptyOption() {
    return ["" => $this->t("Please select")];
  }

  /**
   * Returns the enumeration type.
   *
   * @param \Drupal\ui_patterns_settings\Definition\PatternDefinitionSetting $def
   *   The pattern definition setting.
   *
   * @return string
   *   The enumeration type.
   */
  protected function getEnumerationType(PatternDefinitionSetting $def) {
    return $def->getValue('enumeration_type');
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, $value, PatternDefinitionSetting $def, $form_type) {
    if ($def->getRequired() == FALSE) {
      $options = $this->emptyOption();
    }
    else {
      $options = [];
    }

    $options += $def->getOptions();
    $form[$def->getName()] = [
      '#type' => $this->getEnumerationType($def),
      '#title' => $def->getLabel(),
      '#description' => $def->getDescription(),
      '#default_value' => $this->getValue($value),
      '#options' => $options,
    ];
    $this->handleInput($form[$def->getName()], $def, $form_type);
    return $form;
  }

}
