<?php declare(strict_types = 1);

namespace Drupal\custom_rest\Plugin\rest\resource;

use Drupal\Core\KeyValueStore\KeyValueFactoryInterface;
use Drupal\Core\KeyValueStore\KeyValueStoreInterface;
use Drupal\rest\ModifiedResourceResponse;
use Drupal\rest\Plugin\ResourceBase;
use Drupal\rest\ResourceResponse;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Route;
use Drupal\node\Entity\Node;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @RestResource (
 *   id = "elephantinboardroom_all",
 *   label = @Translation("Custom Rest"),
 *   uri_paths = {
 *     "canonical" = "/api/v1/elephantinboardroom/all"
 *   }
 * )
 */
final class AllContentsResource extends ResourceBase {

  /**
   * The key-value storage.
   */
  private readonly KeyValueStoreInterface $storage;

  /**
   * {@inheritdoc}
   */
  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    array $serializer_formats,
    LoggerInterface $logger,
    KeyValueFactoryInterface $keyValueFactory,
  ) {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $serializer_formats, $logger);
    $this->storage = $keyValueFactory->get('custom_rest_custom_rest');
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition): self {
    return new self(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->getParameter('serializer.formats'),
      $container->get('logger.factory')->get('rest'),
      $container->get('keyvalue')
    );
  }

  /**
   * Responds to GET requests.
   */
  public function get(): ResourceResponse {
    // for(int i=0; i<)
    // if
    $items = \Drupal::entityQuery('node')
    ->condition('status', 1)
    ->accessCheck(TRUE)
    ->condition('type', 'article')
    ->pager(10)
    ->execute();
    // $node = Node::load($id);
    // return new ResourceResponse($node);
    // $text = "TEXT";
    // return new JsonResponse($text);
    $nodes = array();
    foreach($items as $id){
      $nodes[] = Node::load($id);
    }
    return new ResourceResponse($nodes);
  }

}
