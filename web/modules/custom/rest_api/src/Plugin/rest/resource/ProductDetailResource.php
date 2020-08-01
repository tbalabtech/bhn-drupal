<?php

namespace Drupal\rest_api\Plugin\rest\resource;

use Drupal\rest\Plugin\ResourceBase;
use Drupal\rest\ResourceResponse;
use Laminas\Stdlib\ArrayObject;

/**
 * Class ProductDetailResource
 * @package Drupal\rest_api\Plugin\rest\resource
 * @RestResource(
 *   id = "product_detail_resource",
 *   label = @Translation("Product Detail Resource"),
 *   uri_paths = {
 *    "canonical" = "/api/v1/product/{id}"
 *   }
 * )
 */
class ProductDetailResource extends ResourceBase {

  /**
   * @return ResourceResponse
   */
  public function get() {
    $entities = \Drupal::entityTypeManager()
                      ->getStorage('node')
                      ->loadMultiple();

    $result = [];
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
        "category" => implode(',', $categoryList)
      ];

    }
//    print_r($entities);exit;
    $response = new ResourceResponse($result);
    $response->addCacheableDependency($result);
    return $response;
  }

}


