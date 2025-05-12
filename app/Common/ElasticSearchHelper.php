<?php

namespace App\Common;

use Elasticsearch\ClientBuilder;

class ElasticSearchHelper
{
    protected $client;

    public function __construct()
    {
        // Get Elasticsearch hosts from the configuration file
        $hosts = config('elasticsearch.hosts');
        $this->client = ClientBuilder::create()
            ->setHosts($hosts)
            ->build();
    }

    /**
     * Index a single document into Elasticsearch.
     *
     * @param string $index
     * @param string $id
     * @param array  $data
     * @return array
     */
    public function indexDocument(string $index, string $id, array $data): array
    {
        $params = [
            'index' => $index,
            'id'    => $id,
            'body'  => $data,
        ];

        return $this->client->index($params);
    }

    /**
     * Bulk index multiple documents.
     *
     * Each document should be an array with 'id' and 'body' keys.
     *
     * @param string $index
     * @param array  $documents
     * @return array
     */
    public function bulkIndexDocuments(string $index, array $documents): array
    {
        $params = ['body' => []];

        foreach ($documents as $document) {
            // Prepare bulk action metadata
            $params['body'][] = [
                'index' => [
                    '_index' => $index,
                    '_id'    => $document['id'],
                ]
            ];
            // Document data
            $params['body'][] = $document['body'];
        }

        return $this->client->bulk($params);
    }

    /**
     * Retrieve a single document by its ID.
     *
     * @param string $index
     * @param string $id
     * @return array
     */
    public function getDocument(string $index, string $id): array
    {
        $params = [
            'index' => $index,
            'id'    => $id,
        ];

        return $this->client->get($params);
    }

    /**
     * Search for documents using a provided query.
     *
     * @param string $index
     * @param array  $query
     * @return array
     */
    public function searchDocuments(string $index, array $query): array
    {
        $params = [
            'index' => $index,
            'body'  => [
                'query' => $query,
            ],
        ];

        return $this->client->search($params);
    }

    /**
     * Conduct a multi-search query.
     *
     * Each item in $searchQueries should be an array containing:
     * - 'index': the target index.
     * - 'query': the query parameters.
     *
     * @param array $searchQueries
     * @return array
     */
    public function multiSearch(array $searchQueries): array
    {
        $params = ['body' => []];

        foreach ($searchQueries as $search) {
            $params['body'][] = ['index' => $search['index']];
            $params['body'][] = ['query' => $search['query']];
        }

        return $this->client->msearch($params);
    }

    /**
     * Search using various query types.
     *
     * Supported types include: match, match_phrase, fuzzy, etc.
     *
     * @param string $index
     * @param string $searchType (e.g., 'match', 'match_phrase', 'fuzzy')
     * @param array  $options    Options for the query (e.g., field and value)
     * @return array
     */
    public function searchWithType(string $index, string $searchType, array $options): array
    {
        $query = [];

        switch ($searchType) {
            case 'match':
                $query = ['match' => $options];
                break;
            case 'match_phrase':
                $query = ['match_phrase' => $options];
                break;
            case 'fuzzy':
                $query = ['fuzzy' => $options];
                break;
            // Add more types as needed.
            default:
                $query = ['match' => $options];
        }

        $params = [
            'index' => $index,
            'body'  => [
                'query' => $query,
            ],
        ];

        return $this->client->search($params);
    }
}
