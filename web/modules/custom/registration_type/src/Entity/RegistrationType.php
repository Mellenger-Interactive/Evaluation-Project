<?php

namespace Drupal\registration_type\Entity;

use Drupal;
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
 *
 *   id = "registration_type",
 *   label = @Translation("Registration type"),
 *   label_collection = @Translation("Registration types"),
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
 *       "html" = "Drupal\Core\Entity\Routing\AdminHtmlRouteProvider",
 *     }
 *   },
 *   base_table = "registration_type",
 *   admin_permission = "administer registration type",
 *   entity_keys = {
 *     "id" = "id",
 *     "uuid" = "uuid",
 *     "owner" = "uid",
 *     "label" = "name",
 *     "changed" = "changed"
 *   },
 *   links = {
 *     "collection" = "/registration-types",
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
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type): array {

    $fields = parent::baseFieldDefinitions($entity_type);
    // Add the owner field
    $fields += static::ownerBaseFieldDefinitions($entity_type);

    $fields['name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Name'))
      ->setRequired(TRUE)
      ->setDescription(t('The name of the registration type'))
      ->setSetting('max_length', 120)
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

    $fields['display_name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Display Text - Participants'))
      ->setDescription(t('The name displayed to participants when they register'))
      ->setRequired(TRUE)
      ->setSetting('max_length', 120)
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

    $fields['display_name_parents'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Display Text - Parent'))
      ->setDescription(t('The name displayed to parents when they are registering their child'))
      ->setRequired(FALSE)
      ->setSetting('max_length', 120)
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

    $fields['is_returning'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Returning Only?'))
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


    $fields['player_count'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Required Participants'))
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

    $gender_codes = Drupal::getContainer()
      ->get('registration_type.utilities')::vul_gender_codes(TRUE);
    $code_array = Drupal::getContainer()
      ->get('registration_type.utilities')::natural_language_join(array_map(function ($value) {
      return $value->gender_code;
    }, $gender_codes), 'or');

    $fields['gender_mix'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Required Gender Mix'))
      ->setDescription(t(sprintf('The required gender mix for this registration type (using Gender Match not Gender identity). Based on Gender Codes (\'%s\'). To require at least one (F)emale, specify: F. To require Male & Female, specify F-M. Separate allowed options with commas (For instance, a trio could be specified as F OR as M-F-F,F-F-F,M-M-F). These rules will be evaluated during registration.', $code_array)))
      ->setRequired(FALSE)
      ->setSetting('max_length', 120)
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

    $fields['required_registrant_type'] = BaseFieldDefinition::create('list_integer')
      ->setLabel(t('Restrict registration to the following type?'))
      ->setDefaultValue(0)
      ->setSetting('allowed_values', [
        0 => t('No Restriction'),
        2 => t('Adult Player'),
        3 => t('Youth Player'),
        4 => t('Parent')
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

    $fields['status'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Publishing status'))
      ->setDescription(t('A boolean indicating whether the entity is published.'))
      ->setDefaultValue(TRUE);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time that the entity was created.'));

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the entity was last edited.'));

    return $fields;
  }

}
