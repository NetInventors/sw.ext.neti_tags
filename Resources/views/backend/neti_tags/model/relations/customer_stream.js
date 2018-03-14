//{block name="plugins/neti_tags/backend/model/relations/customer_stream"}
Ext.define('Shopware.apps.NetiTags.model.relations.CustomerStream', {
    'extend': 'Shopware.data.Model',

    'configure': function () {
        return {
            'controller': 'NetiTagsCustomerStream'
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
