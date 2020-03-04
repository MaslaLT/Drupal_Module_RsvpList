<?php
/**
 * @file
 * Contains \Drupal\rsvp_list\RsvpSettingsForm
 */
namespace Drupal\rsvp_list\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\HttpFoundation\Request;

class RsvpSettingsForm extends ConfigFormBase {

  /**
   * @inheritDoc
   */
  protected function getEditableConfigNames() {
    return [
      'rsvp_list.settings'
    ];
  }

  /**
   * @inheritDoc
   */
  public function getFormId() {
    return 'rsvp_list_admin_settings';
  }

  public function buildForm(array $form, FormStateInterface $form_state, Request $request = NULL) {
    $types = node_type_get_names();
    $config = $this->config('rsvp_list.settings');
    $form['rsvp_list_types'] = [
      '#type' => 'checkboxes',
      '#title' => $this->t('The content types to enable RSVP Form for'),
      '#default_value' => $config->get('allowed_types'),
      '#options' => $types,
      '#description' => $this->t('On specified node types, an RSVP options
       will be available and can be enabled while that node is being edited.')
    ];
    $form['array_filter'] = [
      '#type' => 'value',
      '#value' => true
    ];
    return parent::buildForm($form, $form_state);
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    $allowed_types = array_filter($form_state->getValue('rsvp_list_types'));
    sort($allowed_types);
    $this->config('rsvp_list.settings')
      ->set('allowed_types', $allowed_types)
      ->save();
    parent::submitForm($form, $form_state);
  }

}
