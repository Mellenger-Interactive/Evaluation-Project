<?php

namespace Drupal\registrationType\Entity;

use Drupal;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\registrationType\registrationTypeInterface;
use Drupal\user\EntityOwnerTrait;

/**
 * Defines the registration type entity class.
 *
 * @ContentEntityType(
 *   id = "registrationType",
 *   label = @Translation("Registration type"),
 *   label_collection = @Translation("Registration types"),
 *   label_singular = @Translation("registration type"),
 *   label_plural = @Translation("registration types"),
 *   label_count = @PluralTranslation(
 *     singular = "@count registration types",
 *     plural = "@count registration types",
 *   ),
 *   handlers = {
 *     "list_builder" = "Drupal\registrationType\RegistrationTypeListBuilder",
 *     "views_data" = "Drupal\views\EntityViewsData",
 *     "form" = {
 *       "add" = "Drupal\registrationType\Form\RegistrationTypeForm",
 *       "edit" = "Drupal\registrationType\Form\RegistrationTypeForm",
 *       "delete" = "Drupal\Core\Entity\ContentEntityDeleteForm",
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\Core\Entity\Routing\AdminHtmlRouteProvider",
 *     }
 *   },
 *   base_table = "registrationType",
 *   admin_permission = "administer registration type",
 *   entity_keys = {
 *     "id" = "id",
 *     "uuid" = "uuid",
 *     "owner" = "uid",
 *     "name" = "name",
 *     "is_active" = "is_active",
 *     "display_name" = "display_name",
 *     "display_name_parents" = "display_name_parents",
 *     "for_team" = "for_team",
 *     "is_returning" = "is_returning",
 *     "player_count" = "player_count",
 *     "required_registrant_type" = "required_registrant_type",
 *     "gender_mix" = "gender_mix",
 *     "status" = "status",
 *     "created" = "creted",
 *     "changed" = "changed"
 *   },
 *   links = {
 *     "collection" = "/admin/content/registration-type",
 *     "add-form" = "/registration-type/add",
 *     "canonical" = "/registration-type/{registrationType}",
 *     "edit-form" = "/registration-type/{registrationType}/edit",
 *     "delete-form" = "/registration-type/{registrationType}/delete",
 *   },
 *   field_ui_base_route = "entity.registrationType.settings",
 * )
 */
class registrationType extends ContentEntityBase implements registrationTypeInterface {

  use EntityChangedTrait;
  use EntityOwnerTrait;

  /**
   * {@inheritdoc}
   */
  public function preSave(EntityStorageInterface $storage) {
    parent::preSave($storage);
    if (!$this->getOwnerId()) {
      // If no owner has been set explicitly, make the anonymous user the owner.
      $this->setOwnerId(0);

    }

  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type): array {

    $fields = parent::baseFieldDefinitions($entity_type);
    // Add the owner field
    $fields += static::ownerBaseFieldDefinitions($entity_type);

    $fields['name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Name'))
      ->setRequired(TRUE)
      ->setDescription(t('Registration Type'))
      ->setSetting('max_length', 255)
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -10,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'string',
        'weight' => -10,
      ])
      ->setDisplayConfigurable('view', TRUE);

   

    $fields['display_name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Display Name of Team'))
      ->setDescription(t('The name of team'))
      ->setRequired(TRUE)
      ->setSetting('max_length',255)
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -8,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'string',
        'weight' => -8,
      ])
      ->setDisplayConfigurable('view', TRUE);

      $fields['for_team'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Team Type?'))
      ->setDefaultValue(FALSE)
      ->setSetting('on_label', 'Enabled')
      ->setDisplayOptions('form', [
        'type' => 'boolean_checkbox',
        'settings' => [
          'display_label' => TRUE,
        ],
        'weight' => -7,
      ])
      ->setName('for_team')
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('view', [
        'type' => 'boolean',
        'label' => 'above',
        'weight' => -7,
        'settings' => [
          'format' => 'enabled-disabled',
        ],
      ])
      ->setDisplayConfigurable('view', TRUE);  

      $fields['player_count'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Number of Players'))
      ->setDefaultValue(0)
      ->setDisplayOptions('form', [
        'weight' => -6,
        'type' => 'text_textfield',
      ])
      ->setDisplayOptions('view', [
        'weight' => -6,
        'type' => 'text',
      ])
      ->setRequired(TRUE)
      ->setDisplayConfigurable('form', TRUE);

      $fields['is_returning'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Returning '))
      ->setDefaultValue(FALSE)
      ->setSetting('on_label', 'Enabled')
      ->setDisplayOptions('form', [
        'type' => 'boolean_checkbox',
        'settings' => [
          'display_label' => TRUE,
        ],
        'weight' => -6,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('view', [
        'type' => 'boolean',
        'label' => 'above',
        'weight' => -6,
        'settings' => [
          'format' => 'enabled-disabled',
        ],
      ])
      ->setDisplayConfigurable('view', TRUE); 
      $fields['required_registrant_type'] = BaseFieldDefinition::create('list_integer')
      ->setLabel(t('Restrict registration to the following type?'))
      ->setDefaultValue(0)
      ->setSetting('allowed_values', [
        0 ,
        2,
        3 ,
        4
      ])
      ->setCardinality(1)
      ->setSetting('on_label', 'Enabled')
      ->setDisplayOptions('form', [
        'type' => 'options_buttons',
        'settings' => [
          'display_label' => TRUE,
        ],
        'weight' => -4,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('view', [
        'type' => 'boolean',
        'label' => 'above',
        'weight' => -4,
        'settings' => [
          'format' => 'enabled-disabled',
        ],
      ])
      ->setRequired(TRUE)
      ->setDisplayConfigurable('view', TRUE);

    
     
      $fields['gender_mix'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Required Gender Mix'))
      ->setDescription(t(sprintf('The required gender make-up for this registration type, base on vul_user_sex gender codes.')))
      ->setRequired(FALSE)
      ->setSetting('max_length', 255)
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -5,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'string',
        'weight' => -5,
      ])
      ->setDisplayConfigurable('view', TRUE); 

      $fields['display_parents_name'] = BaseFieldDefinition::create('string')
      ->setLabel(t(' Parent Name'))
      ->setDescription(t('parent name'))
      ->setRequired(FALSE)
      ->setSetting('max_length', 255)
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -8,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'string',
        'weight' => -8,
      ])
      ->setDisplayConfigurable('view', TRUE);

       

      $fields['is_active'] = BaseFieldDefinition::create('list_integer')
      ->setSetting('allowed_values', [
        t('Active'),
        t('Inactive'),
      ])
      ->setLabel(t('Status'))
      ->setRequired(TRUE)
      ->setDefaultValue(0)
      ->setSetting('on_label', 'Enabled')
      ->setDisplayOptions('form', [
        'settings' => [
          'display_label' => TRUE,
        ],
        'weight' => -9,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('view', [
        'type' => 'boolean',
        'label' => 'above',
        'weight' => -9,
        'settings' => [
          'format' => 'enabled-disabled',
        ],
      ])
      ->setDisplayConfigurable('view', TRUE);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('creation of entity.'));

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('modified at.'));

    return $fields;
  }

}