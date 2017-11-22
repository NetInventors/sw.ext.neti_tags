//{block name="plugin/neti_tags/backend/store/relations/article"}
Ext.define('Shopware.apps.NetiTags.store.relations.Article', {
    'extend':'Shopware.store.Listing',
    'configure': function() {
        return {
            'controller': 'NetiTagsArticles'
        };
    },
    'model': 'Shopware.apps.NetiTags.model.relations.Article'
});
//{/block}
