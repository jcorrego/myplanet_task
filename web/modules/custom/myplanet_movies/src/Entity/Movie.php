<?php

namespace Drupal\myplanet_movies\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\myplanet_movies\MovieInterface;
use Drupal\user\EntityOwnerTrait;

/**
 * Defines the movie entity class.
 *
 * @ContentEntityType(
 *   id = "movie",
 *   label = @Translation("Movie"),
 *   label_collection = @Translation("Movies"),
 *   label_singular = @Translation("movie"),
 *   label_plural = @Translation("movies"),
 *   label_count = @PluralTranslation(
 *     singular = "@count movie",
 *     plural = "@count movies",
 *   ),
 *   handlers = {
 *     "list_builder" = "Drupal\myplanet_movies\MovieListBuilder",
 *     "views_data" = "Drupal\views\EntityViewsData",
 *     "form" = {
 *       "add" = "Drupal\myplanet_movies\Form\MovieForm",
 *       "edit" = "Drupal\myplanet_movies\Form\MovieForm",
 *       "delete" = "Drupal\Core\Entity\ContentEntityDeleteForm",
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\Core\Entity\Routing\AdminHtmlRouteProvider",
 *     }
 *   },
 *   base_table = "movie",
 *   admin_permission = "administer movie",
 *   fieldable = TRUE,
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "title",
 *     "owner" = "uid",
 *   },
 *   links = {
 *     "collection" = "/admin/content/movie",
 *     "add-form" = "/movie/add",
 *     "canonical" = "/movie/{movie}",
 *     "edit-form" = "/movie/{movie}/edit",
 *     "delete-form" = "/movie/{movie}/delete",
 *   },
 *   field_ui_base_route = "entity.movie.settings",
 * )
 */
class Movie extends ContentEntityBase implements MovieInterface {

  use EntityOwnerTrait, EntityChangedTrait;

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
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {

    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['title'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Title'))
      ->setRequired(TRUE)
      ->setSetting('max_length', 255)
      ->setDisplayOptions(
              'form', [
                'type' => 'string_textfield',
                'weight' => -5,
              ]
          )
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions(
              'view', [
                'label' => 'hidden',
                'type' => 'string',
                'weight' => -5,
              ]
          )
      ->setDisplayConfigurable('view', TRUE);

    $fields['release_date'] = BaseFieldDefinition::create('datetime')
      ->setLabel(t('Release date'))
      ->setRequired(TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setDisplayOptions(
              'view', [
                'label' => 'above',
                'type' => 'timestamp',
                'weight' => 20,
              ]
          )
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions(
              'form', [
                'type' => 'datetime_timestamp',
                'weight' => 20,
              ]
          )
      ->setDescription(t('When the movie was released.'));

    $fields['status'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Status'))
      ->setDefaultValue(TRUE)
      ->setSetting('on_label', 'Enabled');

    $fields += static::ownerBaseFieldDefinitions($entity_type);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Authored on'))
      ->setDescription(t('The time that the movie was created.'));

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the movie was last edited.'));

    return $fields;
  }

}
