<?php

namespace Drupal\rest_api\Plugin\rest\resource;

use Drupal\rest\Plugin\ResourceBase;
use Drupal\rest\ResourceResponse;
use Laminas\Stdlib\ArrayObject;

/**
 * Class ProductResource
 * @package Drupal\rest_api\Plugin\rest\resource
 * @RestResource(
 *   id = "product_resource",
 *   label = @Translation("Product Resource"),
 *   uri_paths = {
 *    "canonical" = "/api/v1/products"
 *   }
 * )
 */
class ProductResource extends ResourceBase {

  /**
   * @return ResourceResponse
   */
  public function get() {
    $entities = \Drupal::entityTypeManager()
                      ->getStorage('node')
                      ->loadByProperties(['type' => 'products']);

    $result = [];
//    echo"<pre>";print_r($entities);
    foreach ($entities as $entity) {

      $categoryList = [];
      foreach ($entity->field_category->referencedEntities() as $category) {
        $categoryList[] = $category->getName();
      }

      $result[] = [
        "id" => $entity->id(),
        "title" => $entity->title->value,
        "description" => $entity->body->value,
        "price" => $entity->field_price->value,
        "qty" => $entity->field_stock_quantity->value,
        "category" => implode(',', $categoryList),
        "image" => ($entity->field_image->entity) ? file_create_url($entity->field_image->entity->getFileUri()) : '',
        "sku" => $entity->field_sku->value
      ];

    }
//    print_r($entities);exit;
    $response = new ResourceResponse($result);
    $response->addCacheableDependency($result);
    return $response;
  }

}


