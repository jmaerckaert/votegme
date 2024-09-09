<?php

namespace Drupal\unique_token;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Url;
use Drupal\webform\Entity\Webform;

/**
 * Provides a list controller for the token entity type.
 */
class TokenListBuilder extends EntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('ID');
    $header['token'] = $this->t('Token');
    $header['status'] = $this->t('Status');
    $header['webform_id'] = $this->t('Webform');
    $header['webform_link'] = $this->t('Webform Link');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var \Drupal\unique_token\Entity\Token $entity */
    $row['id'] = $entity->id();
    $row['token'] = $entity->get('token')->value;
    $row['status'] = $entity->get('status')->value ? $this->t('Used') : $this->t('Unused');
    $row['webform_id'] = $entity->get('webform_id')->entity->label();

    // Generate the link to the webform with the token as a query parameter.
    $webform = Webform::load($entity->get('webform_id')->target_id);
    $webform_url = $webform->toUrl()->setAbsolute()->toString();
    $elements = $webform->getElementsDecoded();
    $unique_token_key = NULL;

    foreach ($elements as $key => $element) {
      if ($element['#type'] === 'unique_token_hidden') {
        $unique_token_key = $key;
        break;
      }
    }

    if ($unique_token_key) {
      $webform_link = $webform_url . '?' . $unique_token_key . '=' . $entity->label();
    } else {
      $webform_link = 'error: unique token field not found';
    }

    $row['webform_link'] = [
      'data' => [
        '#markup' => '<a href="' . $webform_link . '">' . $webform_link . '</a>',
      ],
    ];

    return $row + parent::buildRow($entity);
  }
}
