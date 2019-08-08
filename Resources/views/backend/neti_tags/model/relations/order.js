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
        },
        {
            'name': 'lastName',
            'type': 'string'
        },
        {
            'name': 'firstName',
            'type': 'string'
        },
        {
            'name': 'company',
            'type': 'string'
        },
        {
            'name': 'customerEmail',
            'type': 'string'
        },
        {
            'name': 'customerId',
            'type': 'int'
        },
        {
            'name': 'invoiceAmount',
            'type': 'float'
        },
        {
            'name': 'orderTime',
            'type': 'date'
        }
    ]
});
//{/block}
