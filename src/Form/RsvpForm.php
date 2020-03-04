<?php

namespace Drupal\rsvp_list\Form;

use Drupal\Core\Database\Database;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class RsvpForm extends FormBase {

  /**
   * @inheritDoc
   */
  public function getFormId() {
    return 'rsvp_list_form';
  }

  /**
   * @inheritDoc
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $node = \Drupal::routeMatch()->getParameter('node');
    $nid = null;
    if($node != null) {
      $nid = $node->nid->value;
    }

    $form['email'] = [
      '#title' => t('Email Address'),
      '#type' => 'textfield',
      '#size' => 25,
      '#description' => t('We will send updates to the email address you provide.'),
      '#required' => TRUE,
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => t('RSVP'),
    ];

    $form['nid'] = [
      '#type' => 'hidden',
      '#value' => $nid,
    ];

    return $form;
  }

  /**
   * @inheritDoc
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $value = $form_state->getValue('email');
    if ($value == !\Drupal::service('email.validator')->isValid($value)) {
      $form_state->setErrorByName('email', t('The email address %mail is not valid.', ['%mail' => $value]));
    }
    $node = \Drupal::routeMatch()->getParameter('node');
    $select = Database::getConnection()->select('rsvplist', 'r');
    $select->fields('r', ['nid']);
    $select->condition('nid', $node->id());
    $select->condition('mail', $value);
    $results = $select->execute();
    if (!empty($results->fetchCol())) {
      $form_state->setErrorByName('email', t('The address %mail is already subscribed', ['%mail' => $value]));
    }
  }

  /**
   * @inheritDoc
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $user = \Drupal\user\Entity\User::load(\Drupal::currentUser()->id());
    $entity = \Drupal::entityTypeManager()->getStorage('rsvp_list')
      ->create([
        'uid' => $user->id(),
        'nid' => $form_state->getValue('nid'),
        'mail' => $form_state->getValue('email'),
      ]);
    $entity->save();
    \Drupal::messenger()->addMessage(t('You are on the list for this event.'));
  }
}
