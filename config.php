<?php
return [
    'licenses'   => [
        [
            // TODO: Fill in the localKey from the NetiLocalKey Plugin and the license keys from the shopware account dashboard
            's'        => 'Vu+IjjtvHGD2ZGAhx5AZAfz9Q+o=',
            'c'        => 'a2Z/Mlao26bzx/uSUQBtX9erzt8=',
            'localKey' => '7ad8fd3fd828898bee9fbe00c9cfd2e3',
        ],
    ],
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
