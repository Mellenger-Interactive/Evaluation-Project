<?php

namespace Drupal\registration_type\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for the registration type entity edit forms.
 */
class RegistrationTypeForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state): int {
    $result = parent::save($form, $form_state);

    $entity = $this->getEntity();

    $message_arguments = ['%label' => $entity->toLink()->toString()];
    $logger_arguments = [
      '%label' => $entity->label(),
      'link' => $entity->toLink($this->t('View'))->toString(),
    ];

    switch ($result) {
      case SAVED_NEW:
        $this->messenger()
          ->addStatus($this->t('Settings have been saved.', $message_arguments));
        $this->logger('registration_type')
          ->notice('Created new registration type %label', $logger_arguments);
        break;

      case SAVED_UPDATED:
        $this->messenger()
          ->addStatus($this->t('Settings have been updated.', $message_arguments));
        $this->logger('registration_type')
          ->notice('Updated registration type %label.', $logger_arguments);
        break;
    }

    $form_state->setRedirect('entity.registration_type.canonical', ['registration_type' => $entity->id()]);

    return $result;
  }

  public function buildForm(array $form, FormStateInterface $form_state) {


    // Add custom states here.

    $form = parent::buildForm($form, $form_state);
    $form['is_returning']['#states'] = [
      'visible' => [
        ':input[name="for_team[value]"]' => ['checked' => TRUE],
      ],
    ];

    $form['gender_mix']['#states'] = [
      'visible' => [
        'input[name="for_team[value]"]' => ['checked' => FALSE],
      ]
    ];

    return $form;
  }

  /**
   * @param array $form
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *
   * @return \Drupal\Core\Entity\ContentEntityInterface
   */
  function validateForm(array &$form, FormStateInterface $form_state): \Drupal\Core\Entity\ContentEntityInterface {

    $gender_mix = $form_state->getValue('gender_mix');
    $gender_array = explode(',', strtoupper($gender_mix[0]['value']));
    $fixed_array = array();
    foreach($gender_array as $mix) {
      $mix_array = explode('-',trim($mix));
      asort($mix_array);
      $fixed_array[] = implode('-',$mix_array);
    }

    $gender_mix[0]['value'] = implode(',',$fixed_array);
    $form_state->setValue('gender_mix', $gender_mix);

    return parent::validateForm($form, $form_state);
  }
}
