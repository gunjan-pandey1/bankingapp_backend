<?php
namespace App\Http\Controllers;

use App\Services\ElasticsearchService;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    protected $elasticsearch;

    public function __construct(ElasticsearchService $elasticsearch)
    {
        $this->elasticsearch = $elasticsearch;
    }

    public function index()
    {
        $data = [
            'title' => 'Elasticsearch in Laravel',
            'content' => 'This is a sample document.',
        ];

        return $this->elasticsearch->index('articles', 1, $data);
    }

    public function search(Request $request)
    {
        $query = [
            'query' => [
                'match' => ['title' => $request->input('q')],
            ],
        ];

        return $this->elasticsearch->search('articles', $query);
    }
}
