<?php

namespace Drupal\web\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for the web entity edit forms.
 */
class WebForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $result = parent::save($form, $form_state);

    $entity = $this->getEntity();

    $message_arguments = ['%label' => $entity->toLink()->toString()];
    $logger_arguments = [
      '%label' => $entity->label(),
      'link' => $entity->toLink($this->t('View'))->toString(),
    ];

    switch ($result) {
      case SAVED_NEW:
        $this->messenger()->addStatus($this->t('New web %label has been created.', $message_arguments));
        $this->logger('web')->notice('Created new web %label', $logger_arguments);
        break;

      case SAVED_UPDATED:
        $this->messenger()->addStatus($this->t('The web %label has been updated.', $message_arguments));
        $this->logger('web')->notice('Updated web %label.', $logger_arguments);
        break;
    }

    $form_state->setRedirect('entity.web.canonical', ['web' => $entity->id()]);

    return $result;
  }

}
