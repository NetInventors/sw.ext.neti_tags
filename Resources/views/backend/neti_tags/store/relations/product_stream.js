//{block name="plugin/neti_tags/backend/store/relations/product_stream"}
Ext.define('Shopware.apps.NetiTags.store.relations.ProductStream', {
    'extend':'Shopware.store.Listing',
    'configure': function() {
        return {
            'controller': 'NetiTagsProductStream'
        };
    },
    'model': 'Shopware.apps.NetiTags.model.relations.ProductStream'
});
//{/block}
