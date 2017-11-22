/**
 * @copyright  Copyright (c) 2017, Net Inventors GmbH
 * @category   Shopware
 * @author     dpogodda
 */
//{block name="plugin/neti_tags/backend/store/Tag"}
Ext.define('Shopware.apps.NetiTags.store.Tag', {
    'extend': 'Shopware.store.Listing',
    'configure': function () {
        return {
            'controller': 'NetiTagsTag'
        };
    },
    'model': 'Shopware.apps.NetiTags.model.Tag'
});
//{/block}
