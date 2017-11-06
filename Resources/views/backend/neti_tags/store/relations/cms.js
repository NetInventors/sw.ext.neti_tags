//{block name="plugin/neti_tags/backend/store/relations/cms"}
Ext.define('Shopware.apps.NetiTags.store.relations.Cms', {
    'extend':'Shopware.store.Listing',
    'configure': function() {
        return {
            'controller': 'NetiTagsCms'
        };
    },
    'model': 'Shopware.apps.NetiTags.model.relations.Cms'
});
//{/block}
