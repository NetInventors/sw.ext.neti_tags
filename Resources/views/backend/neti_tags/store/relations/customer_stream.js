//{block name="plugin/neti_tags/backend/store/relations/customer_stream"}
Ext.define('Shopware.apps.NetiTags.store.relations.CustomerStream', {
    'extend':'Shopware.store.Listing',
    'configure': function() {
        return {
            'controller': 'NetiTagsCustomerStream'
        };
    },
    'model': 'Shopware.apps.NetiTags.model.relations.CustomerStream'
});
//{/block}
