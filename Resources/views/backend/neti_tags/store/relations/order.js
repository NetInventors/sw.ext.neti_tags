//{block name="plugin/neti_tags/backend/store/relations/order"}
Ext.define('Shopware.apps.NetiTags.store.relations.Order', {
    'extend':'Shopware.store.Listing',
    'configure': function() {
        return {
            'controller': 'NetiTagsOrder'
        };
    },
    'model': 'Shopware.apps.NetiTags.model.relations.Order'
});
//{/block}
