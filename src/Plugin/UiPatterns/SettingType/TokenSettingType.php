<?php

namespace Drupal\ui_patterns_settings\Plugin\UIPatterns\SettingType;

use Drupal\Core\Entity\ContentEntityType;
use Drupal\ui_patterns_settings\Definition\PatternDefinitionSetting;
use Drupal\ui_patterns_settings\Plugin\PatternSettingTypeBase;

/**
 * Token setting type.
 *
 * @UiPatternsSettingType(
 *   id = "token",
 *   label = @Translation("Token")
 * )
 */
class TokenSettingType extends PatternSettingTypeBase {

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, $value, PatternDefinitionSetting $def) {
    $def = $this->getPatternSettingDefinition();
    $value = $this->getValue($value);
    $description = $def->getDescription() != NULL ? $def->getDescription() : "";

    $content_entity_types = [];
    $entity_type_definations = \Drupal::entityTypeManager()->getDefinitions();
    /* @var $definition EntityTypeInterface */
    foreach ($entity_type_definations as $definition) {
      if ($definition instanceof ContentEntityType) {
        $content_entity_types[] = $definition->id();
      }
    }

    $form[$def->getName()] = [
      '#type' => 'container',
    ];

    $form[$def->getName()]['input'] = [
      '#type' => 'textfield',
      '#title' => $def->getLabel(),
      '#description' => $description,
      '#default_value' => $this->getValue($value),
      '#required' => $def->getRequired(),
    ];

    $form[$def->getName()]['token'] = [
      '#theme' => 'token_tree_link',
      '#token_types' => $content_entity_types,
      '#show_restricted' => TRUE,
      '#default_value' => $value,
      '#weight' => 90,
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsPreprocess($value, array $context, PatternDefinitionSetting $def) {
    /** @var \Drupal\Core\Entity\Entity $entity */
    $entity = isset($context['entity']) ? $context['entity'] : NULL;
    $return_value = '';
    if (!empty($value) && $entity !== NULL) {
      if (isset($value['input'])) {
        $value = $value['input'];
      }
      if (is_string($value)) {
        $token_service = \Drupal::token();
        $return_value = $token_service->replace($value, [$entity->getEntityTypeId() => $entity], ['clear' => TRUE]);
      }
    }
    return $return_value;
  }

}
