<?php

namespace Drupal\myplanet_movies\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for the movie entity edit forms.
 */
class MovieForm extends ContentEntityForm {

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
        $this->messenger()->addStatus($this->t('New movie %label has been created.', $message_arguments));
        $this->logger('myplanet_movies')->notice('Created new movie %label', $logger_arguments);
        break;

      case SAVED_UPDATED:
        $this->messenger()->addStatus($this->t('The movie %label has been updated.', $message_arguments));
        $this->logger('myplanet_movies')->notice('Updated movie %label.', $logger_arguments);
        break;
    }

    $form_state->setRedirect('entity.movie.canonical', ['movie' => $entity->id()]);

    return $result;
  }

}
