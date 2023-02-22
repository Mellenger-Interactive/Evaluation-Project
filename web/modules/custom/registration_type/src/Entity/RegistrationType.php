<?php

namespace Drupal\registration_type\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\registration_type\RegistrationTypeInterface;
use Drupal\user\EntityOwnerTrait;

/**
 * Defines the registration type entity class.
 *
 * @ContentEntityType(
 *   id = "registration_type",
 *   label = @Translation("Registration Type"),
 *   label_collection = @Translation("Registration Types"),
 *   label_singular = @Translation("registration type"),
 *   label_plural = @Translation("registration types"),
 *   label_count = @PluralTranslation(
 *     singular = "@count registration types",
 *     plural = "@count registration types",
 *   ),
 *   handlers = {
 *     "list_builder" = "Drupal\registration_type\RegistrationTypeListBuilder",
 *     "views_data" = "Drupal\views\EntityViewsData",
 *     "form" = {
 *       "add" = "Drupal\registration_type\Form\RegistrationTypeForm",
 *       "edit" = "Drupal\registration_type\Form\RegistrationTypeForm",
 *       "delete" = "Drupal\Core\Entity\ContentEntityDeleteForm",
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\registration_type\Routing\RegistrationTypeHtmlRouteProvider",
 *     }
 *   },
 *   base_table = "registration_type",
 *   admin_permission = "administer registration type",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid",
 *     "owner" = "uid",
 *   },
 *   links = {
 *     "collection" = "/admin/content/registration-type",
 *     "add-form" = "/registration-type/add",
 *     "canonical" = "/registration-type/{registration_type}",
 *     "edit-form" = "/registration-type/{registration_type}/edit",
 *     "delete-form" = "/registration-type/{registration_type}/delete",
 *   },
 *   field_ui_base_route = "entity.registration_type.settings",
 * )
 */
class RegistrationType extends ContentEntityBase implements RegistrationTypeInterface {

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
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {

    $fields = parent::baseFieldDefinitions($entity_type);
    $fields['name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Name'))
      ->setDescription(t('The name of the registration type'))
      ->setDisplayOptions('form', array(
       'type' => 'string_textfield',
       'settings' => array(
         'display_label' => TRUE,
       ),
      ))
      ->setDisplayOptions('view', array(
       'label' => 'hidden',
       'type' => 'string',
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setRequired(TRUE);
    $fields['is_active'] = BaseFieldDefinition::create('list_string')
      ->setSetting('allowed_values', ['0'=>'Inactive','1'=>'Active'])
      ->setLabel(t('Status'))
      ->setDefaultValue(TRUE)
      ->setRequired(TRUE)
      ->setDescription(t('Set to inactive to prevent use in new league registration periods'))
      ->setDisplayOptions('form', array(
        'type' => 'options_buttons',
      ))
      ->setCardinality(1)
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);
    $fields['display_name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Display Text - Participants'))
      ->setDescription(t('The name displayed to participants when they register'))
      ->setDisplayOptions('form', array(
       'type' => 'string_textfield',
       'settings' => array(
         'display_label' => TRUE,
       ),
      ))
      ->setDisplayOptions('view', array(
       'label' => 'hidden',
       'type' => 'string',
      ))
      ->setDisplayConfigurable('form', TRUE);
    $fields['display_name_parents'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Display Text - Parent'))
      ->setDescription(t('The name displayed to parents when they are registering their child'))
      ->setDisplayOptions('form', array(
       'type' => 'string_textfield',
       'settings' => array(
         'display_label' => TRUE,
       ),
      ))
      ->setDisplayOptions('view', array(
       'label' => 'hidden',
       'type' => 'string',
      ))
      ->setDisplayConfigurable('form', TRUE);
    $fields['for_team'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Team Type?'))
      ->setDisplayOptions('form', array(
       'type' => 'string_textfield',
       'settings' => array(
         'display_label' => TRUE,
       ),
      ))
      ->setDisplayOptions('view', array(
       'label' => 'hidden',
       'type' => 'string',
      ))
     ->setDisplayConfigurable('form', TRUE);
    $fields['is_returning'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Returning Only?'))
      ->setDisplayOptions('form', array(
       'type' => 'string_textfield',
       'settings' => array(
         'display_label' => TRUE,
       ),
      ))
      ->setDisplayOptions('view', array(
       'label' => 'hidden',
       'type' => 'string',
      ))
     ->setDisplayConfigurable('form', TRUE);
    $fields['player_count'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Required Participants'))
      ->setDisplayOptions('form', array(
       'type' => 'string_textfield',
       'settings' => array(
         'display_label' => TRUE,
       ),
      ))
      ->setDisplayOptions('view', array(
       'label' => 'hidden',
       'type' => 'string',
      ))
     ->setDisplayConfigurable('form', TRUE);
    $fields['gender_mix'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Gender Mix'))
      ->setDescription(t('The required gender mix for this registration type (using Gender Match not Gender identity). Based on Gender Codes (). To require at least one (F)emale, specify: F. To require Male & Female, specify F-M. Separate allowed options with commas (For instance, a trio could be specified as F OR as M-F-F,F-F-F,M-M-F). These rules will be evaluated during registration.'))
      ->setDisplayOptions('form', array(
       'type' => 'string_textfield',
       'settings' => array(
         'display_label' => TRUE,
       ),
      ))
      ->setDisplayOptions('view', array(
       'label' => 'hidden',
       'type' => 'string',
      ))
      ->setDisplayConfigurable('form', TRUE);
    $fields['required_registrant_type'] = BaseFieldDefinition::create('list_string')
      ->setSetting('allowed_values', ['0'=>'No Restriction','2'=>'Adult Player', '3'=>'Youth Player', '4'=>'Parent'])
      ->setLabel(t('Required Registrant Type'))
      ->setDefaultValue(TRUE)
      ->setRequired(TRUE)
      ->setDisplayOptions('form', array(
        'type' => 'options_buttons',
      ))
      ->setCardinality(1)
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);


    $fields['label'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Label'))
      ->setRequired(TRUE)
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

    $fields['status'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Status'))
      ->setDefaultValue(TRUE)
      ->setSetting('on_label', 'Enabled')
      ->setDisplayOptions('form', [
        'type' => 'boolean_checkbox',
        'settings' => [
          'display_label' => FALSE,
        ],
        'weight' => 0,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('view', [
        'type' => 'boolean',
        'label' => 'above',
        'weight' => 0,
        'settings' => [
          'format' => 'enabled-disabled',
        ],
      ])
      ->setDisplayConfigurable('view', TRUE);

    $fields['uid'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Author'))
      ->setSetting('target_type', 'user')
      ->setDefaultValueCallback(static::class . '::getDefaultEntityOwner')
      ->setDisplayOptions('form', [
        'type' => 'entity_reference_autocomplete',
        'settings' => [
          'match_operator' => 'CONTAINS',
          'size' => 60,
          'placeholder' => '',
        ],
        'weight' => 15,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'author',
        'weight' => 15,
      ])
      ->setDisplayConfigurable('view', TRUE);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Authored on'))
      ->setDescription(t('The time that the registration type was created.'))
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'timestamp',
        'weight' => 20,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('form', [
        'type' => 'datetime_timestamp',
        'weight' => 20,
      ])
      ->setDisplayConfigurable('view', TRUE);

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the registration type was last edited.'));

    return $fields;
  }

}
