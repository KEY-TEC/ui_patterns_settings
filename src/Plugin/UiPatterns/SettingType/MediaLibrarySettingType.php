<?php

namespace Drupal\ui_patterns_settings\Plugin\UIPatterns\SettingType;

use Drupal\Core\Field\FieldItemList;
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
    if (intval($value)) {
      $media_id = $value;
      $media = Media::load($media_id);
    }
    else {
      return '';
    }

    $image_style = $def->getValue('image_style');
    if (empty($image_style) === FALSE) {
      $image_uri = $media->field_media_image->entity !== NULL ? $media->field_media_image->entity->uri->value : NULL;
      if ($image_uri !== NULL) {
        return [
          '#theme' => 'image_style',
          '#style_name' => $image_style,
          '#uri' => $image_uri,
        ];
      }
    }

    $responsive_image_style = $def->getValue('responsive_image_style');
    if (empty($responsive_image_style) === FALSE) {
      $image_uri = $media->field_media_image->entity !== NULL ? $media->field_media_image->entity->uri->value : NULL;
      if ($image_uri !== NULL) {
        return [
          '#theme' => 'responsive_image',
          '#responsive_image_style_id' => $responsive_image_style,
          '#uri' => $image_uri,
        ];
      }
    }

    $view_mode = $def->getValue('view_mode');
    if (empty($view_mode)) {
      return $value;
    }
    $view_mode_builder = $this->entityTypeManager->getViewBuilder('media');
    return $view_mode_builder->view($media, $view_mode);
  }

  /**
   * {@inheritdoc}
   */
  public function fieldStorageExposableTypes() {
    return ['entity_reference'];
  }

  public function preprocessExposedField(FieldItemList $items) {
    foreach ($items as $item) {

      if ($item->entity !== NULL) {
        return $item->entity->id();
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
