<?php

namespace App\Http\Controllers;

use App\Services\ElasticsearchService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ElasticsearchController extends Controller
{
    protected ElasticsearchService $elasticsearch;

    public function __construct(ElasticsearchService $elasticsearch)
    {
        $this->elasticsearch = $elasticsearch;
    }

    public function index(Request $request): JsonResponse
    {
        $data = [
            'title' => $request->input('title'),
            'content' => $request->input('content'),
        ];

        $this->elasticsearch->indexDocument('posts', $data);

        return response()->json(['message' => 'Document indexed']);
    }

    public function search(Request $request): JsonResponse
    {
        $query = [
            'match' => [
                'title' => $request->input('query')
            ]
        ];

        switch ($request->input('type')) {
            case 'match':
                // Поиск по совпадению
                $query = [
                    'match' => [
                        'title' => $request->input('query')
                    ]
                ];
                break;

            case 'match_phrase':
                // Фразовый поиск
                $query = [
                    'match_phrase' => [
                        'title' => $request->input('query')
                    ]
                ];
                break;

            case 'term':
                // Поиск точного термина (без анализа)
                $query = [
                    'term' => [
                        'title.keyword' => $request->input('query')
                    ]
                ];
                break;

            case 'multi_match':
                // Поиск по нескольким полям
                $query = [
                    'multi_match' => [
                        'query' => $request->input('query'),
                        'fields' => ['title', 'description', 'content']
                    ]
                ];
                break;

            case 'match_all':
                // Поиск всех документов
                $query = [
                    'match_all' => new \stdClass()
                ];
                break;

            case 'wildcard':
                // Поиск по шаблону с подстановочными символами
                $query = [
                    'wildcard' => [
                        'title' => '*' . $request->input('query') . '*'
                    ]
                ];
                break;

            case 'prefix':
                // Поиск по префиксу
                $query = [
                    'prefix' => [
                        'title' => $request->input('query')
                    ]
                ];
                break;

            case 'fuzzy':
                // Нечеткий поиск для поиска с опечатками
                $query = [
                    'fuzzy' => [
                        'title' => [
                            'value' => $request->input('query'),
                            'fuzziness' => 'AUTO'
                        ]
                    ]
                ];
                break;

            case 'range':
                // Поиск по диапазону (например, для числовых значений)
                $query = [
                    'range' => [
                        'price' => [
                            'gte' => $request->input('min_price', 10),
                            'lte' => $request->input('max_price', 100)
                        ]
                    ]
                ];
                break;

            case 'bool':
                // Комбинированный запрос с несколькими условиями
                $query = [
                    'bool' => [
                        'must' => [
                            ['match' => ['title' => $request->input('query')]]
                        ],
                        'should' => [
                            ['match' => ['description' => $request->input('query')]]
                        ],
                        'must_not' => [
                            ['match' => ['status' => 'archived']]
                        ]
                    ]
                ];
                break;

            case 'exists':
                // Проверка наличия поля
                $query = [
                    'exists' => [
                        'field' => 'title'
                    ]
                ];
                break;
        }

        $results = $this->elasticsearch->search('posts', $query);

        return response()->json($results);
    }
}
