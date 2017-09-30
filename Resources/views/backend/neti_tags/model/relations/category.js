//{block name="plugins/neti_tags/backend/model/relations/category"}
Ext.define('Shopware.apps.NetiTags.model.relations.Category', {
    'extend': 'Shopware.data.Model',

    'configure': function () {
        return {
            'controller': 'NetiTagsCategory'
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
