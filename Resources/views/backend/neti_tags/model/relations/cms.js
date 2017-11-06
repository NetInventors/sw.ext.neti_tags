//{block name="plugins/neti_tags/backend/model/relations/cms"}
Ext.define('Shopware.apps.NetiTags.model.relations.Cms', {
    'extend': 'Shopware.data.Model',

    'configure': function () {
        return {
            'controller': 'NetiTagsCms'
        };
    },

    'fields': [
        {
            'name': 'id',
            'type': 'int'
        },
        {
            'name': 'description',
            'type': 'string'
        }
    ]
});
//{/block}
