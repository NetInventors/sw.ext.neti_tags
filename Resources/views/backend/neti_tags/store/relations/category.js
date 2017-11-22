//{block name="plugin/neti_tags/backend/store/relations/category"}
Ext.define('Shopware.apps.NetiTags.store.relations.Category', {
    'extend':'Shopware.store.Listing',
    'configure': function() {
        return {
            'controller': 'NetiTagsCategory'
        };
    },
    'model': 'Shopware.apps.NetiTags.model.relations.Category'
});
//{/block}
