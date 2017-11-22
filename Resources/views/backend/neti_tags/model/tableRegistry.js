/**
 * @copyright  Copyright (c) 2017, Net Inventors GmbH
 * @category   Shopware
 * @author     dpogodda
 */

//{block name="plugins/neti_tags/backend/model/tableRegistry"}
Ext.define('Shopware.apps.NetiTags.model.TableRegistry', {
    extend: 'Shopware.data.Model',

    configure: function () {
        return {
            controller: 'NetiTagsTag'
            // related: 'Shopware.apps.NetiTags.view.consultant.detail.Association'
        };
    },

    fields: [
        {
            name: 'id',
            type: 'int'
        },
        {
            name: 'table_name',
            type: 'string'
        }
    ]
});
//{/block}