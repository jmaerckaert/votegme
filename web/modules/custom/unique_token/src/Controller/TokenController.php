<?php

namespace Drupal\unique_token\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Link;
use Drupal\Core\Url;
use Drupal\unique_token\Form\TokenBatchCreateForm;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Controller for managing tokens.
 */
class TokenController extends ControllerBase {

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('form_builder')
    );
  }

  /**
   * Constructs a TokenController object.
   *
   * @param \Drupal\Core\Form\FormBuilderInterface $form_builder
   *   The form builder service.
   */
  public function __construct($form_builder) {
    $this->formBuilder = $form_builder;
  }

  /**
   * Displays the token management page.
   */
  public function manageTokens() {
    $build = [];

    // Add the token creation form.
    $build['token_batch_create_form'] = $this->formBuilder->getForm(TokenBatchCreateForm::class);

    // Add the token list.
    $build['token_list'] = [
      '#type' => 'view',
      '#name' => 'tokens',
      '#display_id' => 'default',
      '#arguments' => [],
    ];

    return $build;
  }

}
