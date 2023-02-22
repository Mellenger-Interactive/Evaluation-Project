<?php

namespace Drupal\registration_type;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface defining a registration type entity type.
 */
interface RegistrationTypeInterface extends ContentEntityInterface, EntityOwnerInterface, EntityChangedInterface {

}
