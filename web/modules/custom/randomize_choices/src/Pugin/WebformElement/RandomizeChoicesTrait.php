<?php

namespace Drupal\randomize_choices\Plugin\WebformElement;

trait RandomizeChoicesTrait {

  /**
   * Randomizes the choices if the randomize_choices property is set.
   *
   * @param array $element
   *   The webform element.
   */
  protected function randomizeChoices(array &$element) {
    if (!empty($element['#randomize_choices']) && !empty($element['#options'])) {
      $options = $element['#options'];
      $keys = array_keys($options);
      shuffle($keys);
      $randomized_options = [];
      foreach ($keys as $key) {
        $randomized_options[$key] = $options[$key];
      }
      $element['#options'] = $randomized_options;
    }
  }
}
