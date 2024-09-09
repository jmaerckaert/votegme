<?php

namespace Drupal\randomize_choices\Plugin\WebformElement;

use Drupal\webform\Plugin\WebformElement\Checkboxes;
use Drupal\webform\WebformSubmissionInterface;

/**
 * Provides a 'randomize_checkboxes' element.
 *
 * @WebformElement(
 *   id = "randomize_checkboxes",
 *   label = @Translation("Randomize Checkboxes"),
 *   description = @Translation("Provides a checkboxes element with randomized choices."),
 *   category = @Translation("Options elements"),
 * )
 */
class RandomizeCheckboxes extends Checkboxes {
  use RandomizeChoicesTrait;

  /**
   * {@inheritdoc}
   */
  public function prepare(array &$element, WebformSubmissionInterface $webform_submission = NULL) {
    parent::prepare($element, $webform_submission);
    $this->randomizeChoices($element);
  }
}
