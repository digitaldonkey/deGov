<?php

namespace Drupal\node_action\Form;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\node\Entity\Node;
use Drupal\node_action\AccessChecker\MessagesTrait;
use Drupal\workflows\Entity\Workflow;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;


class ModerationState extends FormBase {

  private $entityTypeManager;

  private const DATETIME_STORAGE_FORMAT = 'Y-m-d\TH:i:s';

  use MessagesTrait;

  use ActionFormTrait;

  public function __construct(EntityTypeManagerInterface $entityTypeManager) {
    $this->entityTypeManager = $entityTypeManager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager')
    );
  }

  public function getFormId(): string {
    return 'moderation_state_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state): array {
    $this->removeMessageFromDefaultAction();

    $entityIds = $this->getRequest()->get('entityIds');

    $nodesList = $this->putTogetherHTMLList($entityIds);

    $form['entity_info'] = [
      '#markup' => $this->t('Nodes which will be affected by this action:') . $nodesList
    ];

    $form['moderation_state'] = [
      '#type'          => 'select',
      '#title'         => $this->t('Moderation state'),
      '#size'          => 5,
      '#required'      => TRUE,
      '#options'       => $this->getAllModerationStates(),
    ];

    $form['entity_ids'] = [
      '#type'  => 'hidden',
      '#value' => json_encode($entityIds),
    ];

    $form['date'] = [
      '#type'           => 'datetime',
      '#title'          => $this->t('Scheduled date'),
      '#description'    => $this->t('The date of the scheduled publish state change. If you provide none, the moderation state will be set immediately.'),
      '#default_value'  => NULL,
      '#date_increment' => 1,
      '#date_timezone'  => drupal_get_user_timezone(),
      '#required'       => FALSE,
    ];

    $form['submit'] = [
      '#type'  => 'submit',
      '#value' => $this->t('Submit'),
    ];

    return $form;
  }

  public function validateForm(array &$form, FormStateInterface $form_state): void {
    parent::validateForm($form, $form_state);

    if (!empty($form_state->getValue('date')) && strtotime($form_state->getValue('date')) < time()) {
      $form_state->setErrorByName('date', $this->t('The date must be in the future.'));
    }
  }

  public function submitForm(array &$form, FormStateInterface $form_state): void {
    $entityIds = json_decode($form_state->getValue('entity_ids'), TRUE);

    foreach ($entityIds as $entityId => $nodeTitle) {

      if (!empty($form_state->getValue('date'))) {
        $this->scheduleModerationStateChange($form_state, $entityId);
        $this->messenger()->addMessage($this->t('Action was performed successfully.') . ' ' . $this->t('Scheduled moderation state change with state @state on @num nodes for date @date.',
          [
            '@state' => $this->getWorkflowLabelByKey($form_state->getValue('moderation_state')),
            '@num'   => \count($entityIds),
            '@date'  => date('d.m.Y, h:i', strtotime($form_state->getValue('date')))
          ])
        );
      }

      if (empty($form_state->getValue('date'))) {
        $this->setModerationState($form_state, $entityId);
        $this->messenger()->addMessage($this->t('Action was performed successfully.') . ' ' . $this->t('Applied moderation change with state @state on @num nodes.',
          [
            '@state' => $this->getWorkflowLabelByKey($form_state->getValue('moderation_state')),
            '@num'   => \count($entityIds),
          ])
        );
      }

    }

    $this->redirectToContentOverview();
  }

  private function getWorkflowLabelByKey(string $key) {
    $editorialWorkflow = $this->entityTypeManager->getStorage('workflow')
      ->loadByProperties(['type' => 'content_moderation']);
    $states = $editorialWorkflow['editorial']->get('type_settings')['states'];

    return $states[$key]['label'];
  }

  private function redirectToContentOverview(): RedirectResponse {
    $redirect_url = new Url('system.admin_content');
    $response = new RedirectResponse($redirect_url->toString());
    $response->send();

    return $response;
  }

  private function getAllModerationStates(): array {
    /**
     * @var Workflow $editorialWorkflow
     */
    $editorialWorkflow = $this->entityTypeManager->getStorage('workflow')
      ->loadByProperties(['type' => 'content_moderation']);

    $options = [];

    foreach ($editorialWorkflow['editorial']->get('type_settings')['states'] as $stateKey => $stateArray) {
      $options[$stateKey] = $stateArray['label'];
    }

    return $options;
  }

  private function scheduleModerationStateChange(FormStateInterface $form_state, int $entityId): void {
    $node = Node::load($entityId);
    $node->set('field_scheduled_publish', [
      'moderation_state' => $form_state->getValue('moderation_state'),
      'value'            => gmdate(self::DATETIME_STORAGE_FORMAT, strtotime($form_state->getValue('date'))),
    ]);
    $node->save();
  }

  private function setModerationState(FormStateInterface $form_state, int $entityId): void {
    $node = Node::load($entityId);
    $node->set('moderation_state', $form_state->getValue('moderation_state'));
    $node->save();
  }

}
