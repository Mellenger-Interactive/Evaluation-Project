<?php

namespace Drupal\registration_type;

use Drupal\Core\Datetime\DateFormatterInterface;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a list controller for the registration type entity type.
 */
class registrationTypeListBuilder extends EntityListBuilder {

  /**
   * The date formatter service.
   *
   * @var \Drupal\Core\Datetime\DateFormatterInterface
   */
  protected $dateFormatter;

  /**
   * Constructs a new RegistrationTypeListBuilder object.
   *
   * @param \Drupal\Core\Entity\EntityTypeInterface $entity_type
   *   The entity type definition.
   * @param \Drupal\Core\Entity\EntityStorageInterface $storage
   *   The entity storage class.
   * @param \Drupal\Core\Datetime\DateFormatterInterface $date_formatter
   *   The date formatter service.
   */
  public function __construct(EntityTypeInterface $entity_type, EntityStorageInterface $storage, DateFormatterInterface $date_formatter) {
    parent::__construct($entity_type, $storage);
    $this->dateFormatter = $date_formatter;
  }

  /**
   * {@inheritdoc}
   */
  public static function createInstance(ContainerInterface $container, EntityTypeInterface $entity_type) {
    return new static(
      $entity_type,
      $container->get('entity_type.manager')->getStorage($entity_type->id()),
      $container->get('date.formatter')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function render() {
    $build['table'] = parent::render();

    $total = $this->getStorage()
      ->getQuery()
      ->accessCheck(FALSE)
      ->count()
      ->execute();

    $build['summary']['#markup'] = $this->t('Total registration types: @total', ['@total' => $total]);
    return $build;
  }

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('ID');
    $header['name'] = $this->t('Registration Type');
    $header['is_active'] = $this->t('Status');
    $header['for_team'] = $this->t('Team?');
    $header['player_count'] = $this->t('Required Participants');
    $header['gender_mix'] = $this->t('Gender Mix');
    $header['required_registrant_type'] = $this->t('Restricted To');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   * @throws \Drupal\Core\Entity\EntityMalformedException
   */
  public function buildRow(EntityInterface $entity): array {
    $required_registrant_type = function($value) {
      switch ($value) {
        case 4:
          return t('Parent');
        case 3:
          return t('Youth');
        case 2:
          return t('Player');
        default:
          return t('None');
      }
    };

    /** @var \Drupal\registration_type\registrationTypeInterface $entity */
    $row[0] = $entity->id();
    $row[1] = $entity->get('name')->value;
    $row[2] = $entity->get('is_active')->value ? $this->t('Active') : $this->t('Inactive');
    $row[3] = $entity->get('for_team')->value ? $this->t('Yes') : $this->t('No');
    $row[4] = $entity->get('player_count')->value;
    $row[5] = $entity->get('gender_mix')->value;
    $row[6] = $required_registrant_type($entity->get('required_registrant_type')->value);
    return $row + parent::buildRow($entity);
  }

}
