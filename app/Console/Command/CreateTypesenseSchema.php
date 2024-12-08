<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Typesense\Client;
use Typesense\Exceptions\TypesenseClientError;

class CreateTypesenseSchema extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'typesense:create-schema';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create Typesense schema for the User model';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Initialize the Typesense client with the necessary configuration
        $client = new Client([
            'api_key' => config('scout.typesense.client-settings.api_key'),
            'nodes' => config('scout.typesense.client-settings.nodes'),
            'nearest_node' => config('scout.typesense.client-settings.nearest_node'),
            'connection_timeout_seconds' => config('scout.typesense.client-settings.connection_timeout_seconds'),
            'healthcheck_interval_seconds' => config('scout.typesense.client-settings.healthcheck_interval_seconds'),
        ]);

        // Define the schema
        $schema = [
            'name' => 'users',
            'fields' => [
                ['name' => 'id', 'type' => 'int'],
                ['name' => 'name', 'type' => 'string'],
                ['name' => 'email', 'type' => 'string'],
                ['name' => 'created_at', 'type' => 'float'], // Change to float for timestamp
            ],
            'default_sorting_field' => 'created_at', // Using created_at now
        ];

        // Try to create the collection
        try {
            $client->collections->create($schema);
            $this->info('Typesense schema created successfully.');
        } catch (TypesenseClientError $e) {
            $this->error('Error creating Typesense schema: ' . $e->getMessage());
        }

        return 0;
    }
}