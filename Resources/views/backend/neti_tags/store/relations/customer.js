//{block name="plugin/neti_tags/backend/store/relations/customer"}
Ext.define('Shopware.apps.NetiTags.store.relations.Customer', {
    'extend':'Shopware.store.Listing',
    'configure': function() {
        return {
            'controller': 'NetiTagsCustomer'
        };
    },
    'model': 'Shopware.apps.NetiTags.model.relations.Customer'
});
//{/block}
