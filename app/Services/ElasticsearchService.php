<?php

namespace App\Services;
use Elastic\Elasticsearch\ClientBuilder;

class ElasticsearchService
{
  protected $client;

  public function __construct()
  {
    $this->client = ClientBuilder::create()
      ->setHosts([config('services.es.host')])
      ->setBasicAuthentication(config('services.es.username'), config('services.es.password'))
      ->setSSLVerification(false)
      ->build();
  }

  /**
   * Test the connection to the Elasticsearch server.
   * 
   * @return string
   */
  public function testConnection()
  {
    $response = $this->client->ping();
    return $response ? 'Connection successful' : 'Connection failed';
  }

  /**
   * Create an index in Elasticsearch with the given name and default settings.
   * 
   * @param string $indexName
   */
  public function createIndex($indexName)
  {
    $params = [
      'index' => $indexName,
      'body' => [
        'settings' => [
          'number_of_shards' => 1,
          'number_of_replicas' => 0
        ],
        'mappings' => [
          'properties' => [
            'title' => [
              'type' => 'text'
            ],
            'content' => [
              'type' => 'text'
            ]
          ]
        ]
      ]
    ];

    return $this->client->indices()->create($params);
  }

  /**
   * Populate an index with the given data.
   * 
   * @param string $indexName
   * @param array $data
   */
  public function populateIndex($indexName, $data)
  {
    $params = [
      'index' => $indexName,
      'body' => $data
    ];

    return $this->client->index($params);
  }

  /**
   * Verify if a document with the given ID exists in the specified index.
   * 
   * @param string $index
   * @param string $id
   * @return array
   */
  private function verifyExists($index, $id)
  {
    $data = $this->client->search([
      'index' => $index,
      'body' => ['query' => ['bool' => ['must' => ['term' => ['id' => $id]]]]]
    ]);
    return $data['hits']['hits'];
  }

  /**
   * Perform a bulk index operation with the given data.
   * 
   * @param string $indexName
   * @param array $data
   * @return array
   * @throws \Exception
   */
  public function bulkIndexData($indexName, $data)
  {
    $params = ['body' => []];

    foreach ($data as $item) {
      $elastic_prop = $this->verifyExists($indexName, $item['id']);

      if (!count($elastic_prop)) {
        $params['body'][] = [
          'create' => [
            '_index' => $indexName
          ]
        ];

        $params['body'][] = $item;
      } else {
        $params['body'][] = [
          'update' => [
            '_index' => $indexName,
            '_id' => $elastic_prop[0]['_id'],
          ]
        ];

        $params['body'][] = ['doc' => $item];
      }
    }

    if (!empty($params['body'])) {
      $response = $this->client->bulk($params);

      if (isset($response['errors']) && $response['errors']) {
        throw new \Exception('Bulk operation failed: ' . json_encode($response['items']));
      }
    }

    return ['message' => 'Bulk operation successful'];
  }

  /**
   * Get paginated data from the specified index.
   * 
   * @param string $indexName
   * @param int $page
   * @param int $pageSize
   * @return array|string
   */
  public function getPaginatedIndexData($indexName, $page = 1, $pageSize = 10)
  {
    $from = ($page - 1) * $pageSize;

    $params = [
      'index' => $indexName,
      'body' => [
        'from' => $from,
        'size' => $pageSize,
        'query' => [
          'match_all' => new \stdClass()
        ]
      ]
    ];
    $response = $this->client->search($params);

    if (isset($response['hits']['hits'])) {
      return [
        'total' => $response['hits']['total']['value'],
        'data' => $response['hits']['hits'],
        'current_page' => $page,
        'per_page' => $pageSize
      ];
    }

    return 'No documents found';
  }

  /**
   * Get data from the specified index by document ID.
   * 
   * @param string $indexName
   * @param string $id
   * @return array
   */
  public function getIndexData($indexName, $id)
  {
    try {
      $params = [
        'index' => $indexName,
        'body' => [
          'query' => [
            'match' => [
              'id' => $id
            ]
          ]
        ]
      ];

      $response = $this->client->search($params);

      if (isset($response['hits']['hits'][0])) {
        return $response['hits']['hits'][0];
      }

      return [];
    } catch (\Throwable $th) {
      abort(404, "Index not found, index elastic data first");
    }

  }

  /**
   * Get all data from the specified index using the Scroll API.
   * 
   * @param string $indexName
   * @param int $batchSize
   * @return array
   * @throws \Exception
   */
  public function getAllIndexData($indexName, $batchSize = 1000): array
  {
    $params = [
      'index' => $indexName,
      'scroll' => '1m',
      'body' => [
        'size' => $batchSize,
        'query' => [
          'match_all' => new \stdClass()
        ]
      ],
      'client' => [
        'headers' => [
          'Accept' => 'application/vnd.elasticsearch+json; compatible-with=8',
          'Content-Type' => 'application/json'
        ]
      ]
    ];

    $allData = [
      'total' => 0,
      'data' => []
    ];

    try {
      $response = $this->client->search($params);

      $allData['total'] = $response['hits']['total']['value'];

      $allData['data'] = array_merge($allData['data'], $response['hits']['hits']);

      while (isset($response['_scroll_id']) && !empty($response['hits']['hits'])) {
        $scrollParams = [
          'scroll_id' => $response['_scroll_id'],
          'scroll' => '1m',
          'client' => [
            'headers' => [
              'Accept' => 'application/vnd.elasticsearch+json; compatible-with=8',
              'Content-Type' => 'application/json'
            ]
          ]
        ];

        $response = $this->client->scroll($scrollParams);
        $allData['data'] = array_merge($allData['data'], $response['hits']['hits']);
      }

      if (isset($response['_scroll_id'])) {
        $this->client->clearScroll(['scroll_id' => $response['_scroll_id']]);
      }

      return $allData;
    } catch (\Exception $e) {
      throw new \Exception('Failed to retrieve all data: ' . $e->getMessage());
    }
  }

  /**
   * Check whether index exists with given name or not
   * 
   * @param string $indexName
   * @return bool
   */
  public function isIndexExists(string $indexName): bool
  {
    $params = [
      'index' => $indexName,
    ];
    return $this->client->indices()->exists($params)->asBool();
  }
}