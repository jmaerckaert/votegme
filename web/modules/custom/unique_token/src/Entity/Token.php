<?php

namespace Drupal\unique_token\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;

/**
 * Defines the Token entity.
 *
 * @ContentEntityType(
 *   id = "token",
 *   label = @Translation("Token"),
 *   base_table = "tokens",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "token",
 *     "status" = "status",
 *     "webform_id" = "webform_id"
 *   },
 *   admin_permission = "administer tokens",
 *   handlers = {
 *     "list_builder" = "Drupal\unique_token\TokenListBuilder",
 *     "form" = {
 *       "delete" = "Drupal\unique_token\Form\TokenDeleteForm"
 *     }
 *   },
 *   links = {
 *     "delete-form" = "/admin/structure/token/{token}/delete"
 *   }
 * )
 */
class Token extends ContentEntityBase {

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['token'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Token'))
      ->setRequired(TRUE);

    $fields['status'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Status'))
      ->setDefaultValue(FALSE);

    $fields['webform_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Webform'))
      ->setSetting('target_type', 'webform')
      ->setRequired(TRUE);

    return $fields;
  }
}
