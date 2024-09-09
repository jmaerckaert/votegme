<?php

namespace Drupal\randomize_choices\Plugin\WebformElement;

use Drupal\webform\Plugin\WebformElement\Radios;
use Drupal\webform\WebformSubmissionInterface;

/**
 * Provides a 'randomize_radios' element.
 *
 * @WebformElement(
 *   id = "randomize_radios",
 *   label = @Translation("Randomize Radios"),
 *   description = @Translation("Provides a radios element with randomized choices."),
 *   category = @Translation("Options elements"),
 * )
 */
class RandomizeRadios extends Radios {
  use RandomizeChoicesTrait;

  /**
   * {@inheritdoc}
   */
  public function prepare(array &$element, WebformSubmissionInterface $webform_submission = NULL) {
    parent::prepare($element, $webform_submission);
    $this->randomizeChoices($element);
  }
}
