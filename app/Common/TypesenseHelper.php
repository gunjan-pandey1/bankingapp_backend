<?php

use Typesense\Client;
use Illuminate\Support\Facades\Log;
use Typesense\Exceptions\TypesenseClientError;

class TypesenseHelper {
    public function initTypesense(): Client
    {
        return new Client([
            'api_key' => config('scout.typesense.client-settings.api_key'),
            'nodes' => config('scout.typesense.client-settings.nodes'),
            'nearest_node' => config('scout.typesense.client-settings.nearest_node'),
            'connection_timeout_seconds' => config('scout.typesense.client-settings.connection_timeout_seconds'),
            'healthcheck_interval_seconds' => config('scout.typesense.client-settings.healthcheck_interval_seconds'),
        ]);
    }

    public function getTypesenseSearch($client,$value,$field,$sort,$count): array 
    {
        if (strlen($value) > 0) {
            
            $searchParameters = [
                'q'        => $value,
                'query_by' => $field,
                'sort_by'  => $sort,
                'per_page' => $count,
            ];

            try {
                $results = $client->collections['users']->documents->search($searchParameters);
                
                return array_map(fn($hit) => $hit['document']['name'], $results['hits']);
            } catch (TypesenseClientError $e) {
                Log::channel('critical')->critical('Exception searching Typesense: ' . $e->getMessage());
                return [];
            }
        } else {
            return [];
        }
    }

    public function createTypesenseDocument($client,$value): array {
        if (strlen($value) > 0) {
            $response = $client->collections['users']->documents->import($value); 
            return $response;
        } else {
            return [];
        }
    }
}
