/**
 * @copyright  Copyright (c) 2017, Net Inventors GmbH
 * @category   Shopware
 * @author     dpogodda
 */

//{block name="plugins/neti_tags/backend/model/tag"}
Ext.define('Shopware.apps.NetiTags.model.Tag', {
    extend: 'Shopware.data.Model',

    configure: function () {
        return {
            controller: 'NetiTagsTag',
            'detail': 'Shopware.apps.NetiTags.view.overview.detail.Container'
        };
    },

    fields: [
        {
            name: 'id',
            type: 'int'
        },
        {
            name: 'title',
            type: 'string'
        },
        {
            name: 'description',
            type: 'string'
        },
        {
            name: 'relations',
            type: 'auto'
        },
        {
            name: 'disabled',
            type: 'bool'
        }
    ]
});
//{/block}
