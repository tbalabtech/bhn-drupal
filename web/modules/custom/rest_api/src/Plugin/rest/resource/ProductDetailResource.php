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
  public function get($id = NULL) {
    $entity = \Drupal::entityTypeManager()
                      ->getStorage('node')
                      ->load($id);

    if ($entity instanceof \Drupal\node\NodeInterface) {

      $categoryList = [];
      foreach ($entity->field_category->referencedEntities() as $category) {
        $categoryList[] = $category->getName();
      }

      $result = [
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

    $response = new ResourceResponse($result);
    $response->addCacheableDependency($result);
    return $response;
  }

}


