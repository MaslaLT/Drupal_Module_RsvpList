<?php

namespace Drupal\rsvp_list\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;

/**
 * Defines the Advertiser entity.
 *
 * @ingroup advertiser
 *
 * @ContentEntityType(
 *   id = "rsvp_list",
 *   label = @Translation("Rsvp list"),
 *   base_table = "rsvplist",
 *   entity_keys = {
 *     "id" = "id",
 *     "uid" = "uid",
 *     "nid" = "nid",
 *     "mail" = "mail",
 *     "created" = "created"
 *   },
 * )
 */
class RsvpList extends ContentEntityBase implements ContentEntityInterface{

  /**
   * @param \Drupal\Core\Entity\EntityTypeInterface $entity_type
   *
   * @return array|\Drupal\Core\Field\FieldDefinitionInterface[]|mixed
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {

    // Standard field, used as unique if primary index.
    $fields['id'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('ID'))
      ->setDescription(t('The ID of the Advertiser entity.'))
      ->setReadOnly(TRUE);

    // Standard field, unique outside of the scope of the current project.
    $fields['uid'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('UID'))
      ->setDescription(t('The {users}.uid that added this rsvp'))
      ->setReadOnly(TRUE);

    $fields['nid'] = BaseFieldDefinition::create('string')
      ->setLabel(t('NID'))
      ->setDescription(t('The {node} .nid for this rsvp.'))
      ->setSettings([
        'max_length' => 255,
        'text_processing' => 0,
      ])
      ->setDefaultValue(NULL)
      ->setReadOnly(TRUE);

    // Standard field, unique outside of the scope of the current project.
    $fields['mail'] = BaseFieldDefinition::create('email')
      ->setLabel(t('Email'))
      ->setDescription(t('The Node Id of the Advertiser entity.'))
      ->setReadOnly(TRUE);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The Node Id of the Advertiser entity.'))
      ->setReadOnly(TRUE);

    return $fields;
  }

}
