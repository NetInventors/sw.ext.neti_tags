<?php
return [
    'models'     => [
        NetiTags\Models\Tag::class,
        NetiTags\Models\TableRegistry::class,
        NetiTags\Models\Relation::class,
    ],
    'attributes' => [
        [
            'table'  => 's_articles_attributes',
            'suffix' => 'tags',
            'type'   => 'int',
            'data'   => [
                'label'            => [
                    'de_DE' => 'Tags',
                    'en_GB' => 'Tags',
                ],
                'entity'           => 'NetiTags\Models\Tag',
                'displayInBackend' => true
            ]
        ],
        [
            'table'  => 's_user_attributes',
            'suffix' => 'tags',
            'type'   => 'int',
            'data'   => [
                'label'            => [
                    'de_DE' => 'Tags',
                    'en_GB' => 'Tags',
                ],
                'entity'           => 'NetiTags\Models\Tag',
                'displayInBackend' => true
            ]
        ],
        [
            'table'  => 's_blog_attributes',
            'suffix' => 'tags',
            'type'   => 'int',
            'data'   => [
                'label'            => [
                    'de_DE' => 'Tags',
                    'en_GB' => 'Tags',
                ],
                'entity'           => 'NetiTags\Models\Tag',
                'displayInBackend' => true
            ]
        ],
        [
            'table'  => 's_cms_static_attributes',
            'suffix' => 'tags',
            'type'   => 'int',
            'data'   => [
                'label'            => [
                    'de_DE' => 'Tags',
                    'en_GB' => 'Tags',
                ],
                'entity'           => 'NetiTags\Models\Tag',
                'displayInBackend' => true
            ]
        ],
        [
            'table'  => 's_categories_attributes',
            'suffix' => 'tags',
            'type'   => 'int',
            'data'   => [
                'label'            => [
                    'de_DE' => 'Tags',
                    'en_GB' => 'Tags',
                ],
                'entity'           => 'NetiTags\Models\Tag',
                'displayInBackend' => true
            ]
        ],
        [
            'table'  => 's_product_streams_attributes',
            'suffix' => 'tags',
            'type'   => 'int',
            'data'   => [
                'label'            => [
                    'de_DE' => 'Tags',
                    'en_GB' => 'Tags',
                ],
                'entity'           => 'NetiTags\Models\Tag',
                'displayInBackend' => true
            ]
        ],
        [
            'table'  => 's_customer_streams_attributes',
            'suffix' => 'tags',
            'type'   => 'int',
            'data'   => [
                'label'            => [
                    'de_DE' => 'Tags',
                    'en_GB' => 'Tags',
                ],
                'entity'           => 'NetiTags\Models\Tag',
                'displayInBackend' => true
            ]
        ],
    ],
    'menu'       => [
        [
            'label'      => [
                'de_DE' => 'Tags',
                'en_GB' => 'Tags',
            ],
            'controller' => 'NetiTags',
            'class'      => 'sprite-tags-label',
            'action'     => 'Index',
            'active'     => 1,
            'parent'     => 'Inhalte',
        ],
    ],
];
