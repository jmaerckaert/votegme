<?php

namespace Drupal\unique_token\Plugin\WebformElement;

use Drupal\webform\Plugin\WebformElement\Hidden;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a 'unique_token_hidden' element.
 *
 * @WebformElement(
 *   id = "unique_token_hidden",
 *   label = @Translation("Unique Token Hidden"),
 *   description = @Translation("Provides a hidden form element that generates a unique token."),
 *   category = @Translation("Hidden elements"),
 * )
 */
class UniqueTokenHidden extends Hidden {

  /**
   * {@inheritdoc}
   */
  public function initialize(array &$element) {
    parent::initialize($element);
    if (!empty($element['#unique_token'])) {
      $element['#value'] = \Drupal::service('unique_token.token_manager')->generateToken($element['#webform_id']);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function getDefaultProperties() {
    return [
        'prepopulate' => TRUE,
      ] + parent::getDefaultProperties();
  }

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildConfigurationForm($form, $form_state);

    // Set the prepopulate checkbox to be checked and disabled.
    $form['advanced']['prepopulate'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Prepopulate'),
      '#default_value' => TRUE,
      '#disabled' => TRUE,
      '#description' => $this->t('This option is automatically enabled for unique token hidden elements.'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateElement(array &$element, FormStateInterface $form_state, array &$complete_form) {
    $token = $form_state->getValue($element['#name']);
    $validation = \Drupal::service('unique_token.token_manager')->validateToken($token);

    if ($validation == 'invalid') {
      $form_state->setError($element, t('Jeton non valide.'));
    } elseif ($validation == 'used') {
      $form_state->setError($element, t('Jeton déjà utilisé.'));
    }

    // Ensure prepopulate is always TRUE.
    $element['#prepopulate'] = TRUE;
  }
}
