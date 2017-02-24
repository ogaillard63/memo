<?php
require 'vendor/autoload.php';

use Elasticsearch\ClientBuilder;



$params = [
    'index' => 'support',
    'body' => [
        'settings' => [
            // Simple settings for now, single shard
            'number_of_shards' => 1,
            'number_of_replicas' => 0,
            'analysis' => [
                'filter' => [
                    'shingle' => [
                        'type' => 'shingle'
                    ],
                    'french_elision' => [
                        'type' => 'elision',
                        'articles_case' => true,
                        'articles' => [
                        	'l', 'm', 't', 'qu', 'n', 's',
                        	'j', 'd', 'c', 'jusqu', 'quoiqu',
                        	'lorsqu', 'puisqu']
                    ],
					 'french_stemmer' => [
                        'type' => 'stemmer',
                        'name' => 'light_french'
                    ],
                ],
        	'analyzer' => [
                    'custom_french_analyzer' => [
                        'tokenizer' => 'letter',
                        'filter' => ['asciifolding', 'lowercase', 'french_stemmer', 'french_elision', 'stop']
					],
					'tag_analyzer' => [
                        'tokenizer' => 'keyword',
                        'filter' => ['asciifolding', 'lowercase']
					],
                    'my_ngram_analyzer' => [
                        'tokenizer' => 'my_ngram_tokenizer',
                        'filter' => 'lowercase',
                    ]
                ],
                // Allow searching for partial names with nGram
                'tokenizer' => [
                    'my_ngram_tokenizer' => [
                        'type' => 'nGram',
                        'min_gram' => 3,
                        'max_gram' => 20,
                        'token_chars' => ['letter', 'digit']
                    ]
                ]
            ]
        ],
        'mappings' => [
            '_default_' => [
                'properties' => [
                    'title' => [
                        'type' => 'text',
                        'fielddata' => 'true',
                        'analyzer' => 'custom_french_analyzer',
                    ],
                    'comment' => [
                        'type' => 'text',
                        'fielddata' => 'true',
                        'analyzer' => 'custom_french_analyzer',
                    ],
                    'tags' => [
                        'type' => 'text',
                        'analyzer' => 'tag_analyzer',
                    ],
                    'last_update' => [
                        'type' => 'date',
                        'index' => 'not_analyzed',
                    ],
                ]
            ],
        ]
    ]
];

$deleteParams = [
    'index' => 'support'
];

$client = ClientBuilder::create()->build();
$response = $client->indices()->delete($deleteParams);
print_r($response);
echo "<hr/>";


// Create index `memo` with ngram indexing
$client->indices()->create($params);
print_r($response);

?>