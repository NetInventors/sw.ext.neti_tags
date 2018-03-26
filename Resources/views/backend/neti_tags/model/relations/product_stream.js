//{block name="plugins/neti_tags/backend/model/relations/product_stream"}
Ext.define('Shopware.apps.NetiTags.model.relations.ProductStream', {
    'extend': 'Shopware.data.Model',

    'configure': function () {
        return {
            'controller': 'NetiTagsProductStream'
        };
    },

    'fields': [
        {
            'name': 'id',
            'type': 'int'
        },
        {
            'name': 'name',
            'type': 'string'
        }
    ]
});
//{/block}
