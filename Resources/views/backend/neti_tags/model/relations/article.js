//{block name="plugins/neti_tags/backend/model/relations/article"}
Ext.define('Shopware.apps.NetiTags.model.relations.Article', {
    'extend': 'Shopware.data.Model',

    'configure': function () {
        return {
            'controller': 'NetiTagsArticles'
        };
    },

    'fields': [
        {
            'name': 'id',
            'type': 'int'
        },
        {
            'name': 'active',
            'type': 'bool'
        },
        {
            'name': 'name',
            'type': 'string'
        },
        {
            'name': 'number',
            'type': 'string'
        }
    ]
});
//{/block}
