<?php
return [
    'licenses' => [
        [
            // TODO: Fill in the localKey from the NetiLocalKey Plugin and the license keys from the shopware account dashboard
            's'        => 'Vu+IjjtvHGD2ZGAhx5AZAfz9Q+o=',
            'c'        => 'a2Z/Mlao26bzx/uSUQBtX9erzt8=',
            'localKey' => '7ad8fd3fd828898bee9fbe00c9cfd2e3',
        ],
    ],
    'models'   => [
        NetiTags\Models\Tag::class,
        NetiTags\Models\TableRegistry::class,
        NetiTags\Models\Relation::class,
    ],
    'menu'     => [
        [
            'label'      => [
                'de_DE' => 'NetiTags',
                'en_GB' => 'NetiTags',
            ],
            'controller' => 'NetiTags',
            'class'      => 'sprite-tags-label',
            'action'     => 'Index',
            'active'     => 1,
            'parent'     => 'Inhalte',
        ],
    ],
    'form'     => [
        [
            'type'        => 'boolean',
            'name'        => 'activeForSubshop',
            'label'       => [
                'de_DE' => 'Plugin für diesen Subshop aktivieren',
                'en_GB' => 'Enable plugin for this subshop',
            ],
            'description' => [
                'de_DE' => '',
                'en_GB' => '',
            ],
            'value'       => true,
            'scope'       => Shopware\Models\Config\Element::SCOPE_SHOP,
        ],
    ],
];