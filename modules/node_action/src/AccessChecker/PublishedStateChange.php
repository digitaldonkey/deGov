<?php

namespace Drupal\node_action\AccessChecker;

use Drupal\content_moderation\Plugin\Field\ModerationStateFieldItemList;
use Drupal\node\NodeInterface;
use Drupal\node_action\UserInteractionFacade;


class PublishedStateChange {

  use MessagesTrait;

  /**
   * @var UserInteractionFacade
   */
  private $userInteractionFacade;

  public function __construct(UserInteractionFacade $userInteractionFacade) {

    $this->userInteractionFacade = $userInteractionFacade;
  }

  public function isAllowed(NodeInterface $node): bool {
    if (!$this->hasModerationState($node)) {
      $this->addMessageForNoModerationState($node);

      return FALSE;
    }

    if ($this->isPublishedModerationState($node) && \in_array('editor', $this->userInteractionFacade->currentUser->getRoles(), TRUE)) {
      $this->addMessageForRestriction($node);

      return FALSE;
    }

    return TRUE;
  }

  private function hasModerationState(NodeInterface $node): bool {
    if (($moderationStateList = $node->get('moderation_state')) instanceof ModerationStateFieldItemList && $moderationStateList->count() === 0) {
      return FALSE;
    }

    return TRUE;
  }

  private function isPublishedModerationState(NodeInterface $node): bool {
    if (!($moderationStateList = $node->get('moderation_state')) instanceof ModerationStateFieldItemList) {
      $this->userInteractionFacade->messenger->addMessage($this->userInteractionFacade->stringTranslationAdapter->t(self::$messageNoUnpublishPermission, ['@nodeTitle' => $node->getTitle()]), 'warning', FALSE);

      return FALSE;
    }

    if (!empty($moderationState = $moderationStateList->first()
        ->getValue()) && $moderationState['value'] === 'published') {
      return TRUE;
    }

    return FALSE;
  }

  private function addMessageForNoModerationState(NodeInterface $node): void {
    $this->userInteractionFacade->messenger->addMessage($this->userInteractionFacade->stringTranslationAdapter->t(self::$messageNoModerationStatesForContentEntityType, ['@title'             => $node->getTitle(),
                                                                                                                                                                         '@contentEntityType' => $node->getType(),
    ]), 'warning', FALSE);
  }

  private function addMessageForRestriction(NodeInterface $node): void {
    $this->userInteractionFacade->messenger->addMessage($this->userInteractionFacade->stringTranslationAdapter->t(self::$messageNoUnpublishPermission, ['@nodeTitle' => $node->getTitle()]), 'warning', FALSE);
  }

}
