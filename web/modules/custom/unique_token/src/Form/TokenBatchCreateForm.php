<?php

namespace Drupal\unique_token\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\webform\Entity\Webform;
use Drupal\unique_token\Entity\Token;

/**
 * Form for creating multiple tokens.
 */
class TokenBatchCreateForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'token_batch_create_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['quantity'] = [
      '#type' => 'number',
      '#title' => $this->t('Quantity'),
      '#description' => $this->t('Enter the number of tokens to create.'),
      '#required' => TRUE,
      '#min' => 1,
    ];

    $form['webform_id'] = [
      '#type' => 'select',
      '#title' => $this->t('Webform'),
      '#description' => $this->t('Select the webform to associate with the tokens.'),
      '#options' => $this->getWebformsWithUniqueTokenHidden(),
      '#required' => TRUE,
    ];

    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Create Tokens'),
      '#button_type' => 'primary',
    ];

    return $form;
  }

  /**
   * Get webforms that use the unique_token_hidden element.
   *
   * @return array
   *   An array of webform titles keyed by webform IDs.
   */
  protected function getWebformsWithUniqueTokenHidden() {
    $webforms = Webform::loadMultiple();
    $options = [];

    foreach ($webforms as $webform) {
      $elements = $webform->getElementsDecoded();
      foreach ($elements as $element) {
        if ($element['#type'] === 'unique_token_hidden') {
          $options[$webform->id()] = $webform->label();
          break;
        }
      }
    }

    return $options;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $quantity = $form_state->getValue('quantity');
    $webform_id = $form_state->getValue('webform_id');

    for ($i = 0; $i < $quantity; $i++) {
      $token = Token::create([
        'token' => \Drupal::service('uuid')->generate(),
        'status' => 0,
        'webform_id' => $webform_id,
      ]);
      $token->save();
    }

    $this->messenger()->addMessage($this->t('@count tokens have been created for the webform @webform.', [
      '@count' => $quantity,
      '@webform' => $webform_id,
    ]));
  }

}
