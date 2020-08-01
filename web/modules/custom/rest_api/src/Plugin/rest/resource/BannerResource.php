<?php

namespace Drupal\rest_api\Plugin\rest\resource;

use Drupal\rest\Plugin\ResourceBase;
use Drupal\rest\ResourceResponse;
use Laminas\Stdlib\ArrayObject;

/**
 * Class BannerResource
 * @package Drupal\rest_api\Plugin\rest\resource
 * @RestResource(
 *   id = "banner_resource",
 *   label = @Translation("Banner Resource"),
 *   uri_paths = {
 *    "canonical" = "/api/v1/banner"
 *   }
 * )
 */
class BannerResource extends ResourceBase {

  /**
   * @return ResourceResponse
   */
  public function get() {
    $entities = \Drupal::entityTypeManager()
                      ->getStorage('node')
                      ->loadByProperties(['type' => 'banner']);

    $result = [];
    foreach ($entities as $entity) {

      $result[] = [
        "title" => $entity->title->value,
        "image" => file_create_url($entity->field_image->entity->getFileUri()),
      ];

    }
//    print_r($entities);exit;
    $response = new ResourceResponse($result);
    $response->addCacheableDependency($result);
    return $response;
  }

}


