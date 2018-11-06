//{block name="plugins/neti_tags/backend/model/relations/order"}
Ext.define('Shopware.apps.NetiTags.model.relations.Order', {
    'extend': 'Shopware.data.Model',

    'configure': function () {
        return {
            'controller': 'NetiTagsOrders'
        };
    },

    'fields': [
        {
            'name': 'id',
            'type': 'int'
        },
        {
            'name': 'number',
            'type': 'string'
        }
    ]
});
//{/block}
