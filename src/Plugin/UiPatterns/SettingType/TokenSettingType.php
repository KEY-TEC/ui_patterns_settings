<?php

namespace Drupal\ui_patterns_settings\Plugin\UIPatterns\SettingType;

use Drupal\Core\Entity\ContentEntityType;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Utility\Token;
use Drupal\ui_patterns_settings\Definition\PatternDefinitionSetting;
use Drupal\ui_patterns_settings\Plugin\PatternSettingTypeBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Token setting type.
 *
 * @UiPatternsSettingType(
 *   id = "token",
 *   label = @Translation("Token")
 * )
 */
class TokenSettingType extends PatternSettingTypeBase implements ContainerFactoryPluginInterface {

  /**
   * The token service.
   *
   * @var \Drupal\Core\Utility\Token
   */
  protected $tokenService;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    $plugin = parent::create($container, $configuration, $plugin_id, $plugin_definition);
    $plugin->setTokenService($container->get('token'));
    return $plugin;
  }

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
    $return_value = '';

    if (isset($value['input'])) {
      $value = $value['input'];
    }

    if (is_string($value)) {
      $token_data = [];
      /** @var \Drupal\Core\Entity\EntityInterface $entity */
      $entity = isset($context['entity']) ? $context['entity'] : NULL;
      if ($entity !== NULL) {
        $token_data[$entity->getEntityTypeId()] = $entity;
      }
      $return_value = $this->getTokenService()->replace($value, $token_data, ['clear' => TRUE]);
    }

    return $return_value;
  }

  /**
   * Sets the token service.
   *
   * @param \Drupal\Core\Utility\Token $token_service
   *   The token service.
   *
   * @return self
   *   The token service.
   */
  public function setTokenService(Token $token_service) {
    $this->tokenService = $token_service;
    return $this;
  }

  /**
   * Gets the token service.
   *
   * @return \Drupal\Core\Utility\Token
   *   The token service.
   */
  public function getTokenService() {
    if (!$this->tokenService) {
      $this->tokenService = \Drupal::token();
    }

    return $this->tokenService;
  }

}
