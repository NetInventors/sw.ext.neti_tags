//{block name="plugins/neti_tags/backend/model/relations/blog"}
Ext.define('Shopware.apps.NetiTags.model.relations.Blog', {
    'extend': 'Shopware.data.Model',

    'configure': function () {
        return {
            'controller': 'NetiTagsBlog'
        };
    },

    'fields': [
        {
            'name': 'id',
            'type': 'int'
        },
        {
            'name': 'title',
            'type': 'string'
        }
    ]
});
//{/block}
