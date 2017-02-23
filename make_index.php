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
                    ]
                ],
        	'analyzer' => [
                    'ccustom_french_analyzer' => [
                        'tokenizer' => 'letter',
                        'filter' => ['asciifolding', 'lowercase', 'french_stem', 'elision', 'stop']
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
                        'min_gram' => 1,
                        'max_gram' => 15,
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
                        'analyzer' => 'ccustom_french_analyzer',
                        'term_vector' => 'yes',
                        'copy_to' => 'combined'
                    ],
                    'comment' => [
                        'type' => 'text',
                        'analyzer' => 'ccustom_french_analyzer',
                        'term_vector' => 'yes',
                        'copy_to' => 'combined'
                    ],
                    'tags' => [
                        'type' => 'text',
                        'analyzer' => 'tag_analyzer',
                        'term_vector' => 'yes',
                        'copy_to' => 'combined'
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
// Create index `memo` with ngram indexing
$client = ClientBuilder::create()->build();
$client->indices()->create($params);

?>