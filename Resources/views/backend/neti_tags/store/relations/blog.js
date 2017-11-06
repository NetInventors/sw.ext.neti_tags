//{block name="plugin/neti_tags/backend/store/relations/blog"}
Ext.define('Shopware.apps.NetiTags.store.relations.Blog', {
    'extend':'Shopware.store.Listing',
    'configure': function() {
        return {
            'controller': 'NetiTagsBlog'
        };
    },
    'model': 'Shopware.apps.NetiTags.model.relations.Blog'
});
//{/block}
