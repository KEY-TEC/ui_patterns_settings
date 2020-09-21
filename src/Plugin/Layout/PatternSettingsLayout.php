<?php

namespace Drupal\ui_patterns_settings\Plugin\Layout;

use Drupal\ui_patterns_layouts\Plugin\Layout\PatternLayout;

/**
 * Class PatternSettingsLayout.
 *
 * @package Drupal\ui_patterns_settings\Plugin\Layout
 */
class PatternSettingsLayout extends PatternLayout {

  /**
   * {@inheritdoc}
   */
  public function build(array $regions) {
    $build = parent::build($regions);
    $configuration = $this->getConfiguration();
    if (isset($configuration['pattern']['settings'])) {
      $build['#settings'] = $configuration['pattern']['settings'];
    }
    if (isset($configuration['pattern']['variant_token'])) {
      $build['#variant_token'] = $configuration['pattern']['variant_token'];
    }
    return $build;
  }

}
