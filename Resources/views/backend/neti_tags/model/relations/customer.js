//{block name="plugins/neti_tags/backend/model/relations/customer"}
Ext.define('Shopware.apps.NetiTags.model.relations.Customer', {
    'extend': 'Shopware.data.Model',

    'configure': function () {
        return {
            'controller': 'NetiTagsCustomers'
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
            'name': 'email',
            'type': 'string'
        },
        {
            'name': 'firstname',
            'type': 'string'
        },
        {
            'name': 'lastname',
            'type': 'string'
        }
    ]
});
//{/block}
