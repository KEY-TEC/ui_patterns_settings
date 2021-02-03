<?php

namespace Drupal\ui_patterns_settings\Plugin\UIPatterns\SettingType;

use Drupal\media\Entity\Media;
use Drupal\ui_patterns_settings\Definition\PatternDefinitionSetting;
use Drupal\ui_patterns_settings\Plugin\PatternSettingTypeBase;

/**
 * Media Library setting type.
 *
 * @UiPatternsSettingType(
 *   id = "media_library",
 *   label = @Translation("Media Library")
 * )
 */
class MediaLibrarySettingType extends PatternSettingTypeBase {

  /**
   * {@inheritdoc}
   */
  protected function getSettingTypeDependencies() {
    return ['media_library_form_element'];
  }

  /**
   * {@inheritdoc}
   */
  public function settingsPreprocess($value, array $context, PatternDefinitionSetting $def) {
    $view_mode = $def->getValue('view_mode');
    if (empty($view_mode)) {
      return $value;
    }
    elseif (intval($value)) {
      $media_id = $value;
      $media = Media::load($media_id);
      if ($media !== NULL) {
        $view_mode_builder = $this->entityTypeManager->getViewBuilder('media');
        return $view_mode_builder->view($media, $view_mode);
      }
      else {
        return '';
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, $value, PatternDefinitionSetting $def, $form_type) {

    $form[$def->getName()] = [
      '#type' => 'media_library',
      '#title' => $def->getLabel(),
      '#description' => $def->getDescription(),
      '#default_value' => $this->getValue($value),
    ];
    $allowed_bundles = $def->getValue('allowed_bundles');
    if (!empty($allowed_bundles)) {
      $form[$def->getName()]['#allowed_bundles'] = $allowed_bundles;
    }
    else {
      $form[$def->getName()]['#allowed_bundles'] = ['image'];
    }
    $cardinality = $def->getValue('cardinality');
    if (!empty($cardinality)) {
      $form[$def->getName()]['#cardinality'] = $cardinality;
    }
    $this->handleInput($form[$def->getName()], $def, $form_type);
    return $form;
  }

}
