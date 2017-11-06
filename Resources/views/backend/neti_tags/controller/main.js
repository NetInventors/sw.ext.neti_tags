/**
 * @copyright  Copyright (c) 2017, Net Inventors GmbH
 * @category   Shopware
 * @author     dpogodda
 */

/**
 * glob: Ext, Shopware
 */
//{block name="plugins/neti_tags/backend/controller/main"}
Ext.define('Shopware.apps.NetiTags.controller.Main', {
    extend: 'Enlight.app.Controller',

    init: function() {
        var me = this,
            action = me.subApplication.action && me.subApplication.action.toLowerCase();

        switch (action)
        {
            case 'overview':
            default:
                me.getController('Overview').createMainWindow();
        }

        me.callParent(arguments);
    }
});
//{/block}