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
    'form' => [
        [
            'scope'       => \Shopware\Models\Config\Element::SCOPE_SHOP,
            'name'        => 'deleteprotecting',
            'value'       => true,
            'isRequired'  => true,
            'type'        => 'checkbox',
            'label'       => [
                'de_DE' => 'Löschschutz',
                'en_GB' => 'Delete protection',
            ],
            'description' => [
                'de_DE' => 'Ist der Löschschutz aktiviert, kann ein Tag nicht gelöscht werden sofern dieser Tag noch Entitäten zugewiesen ist',
                'en_GB' => 'If the protection is enabled, a tag can not be deleted if that tag is still assigned to any entities'
            ],
        ],
    ]
];
