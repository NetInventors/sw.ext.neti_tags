/**
 * @copyright  Copyright (c) 2017, Net Inventors GmbH
 * @category   Shopware
 * @author     dpogodda
 */

//{namespace name="plugins/neti_tags/backend/overview"}
//{block name="plugins/neti_tags/backend/controller/overview"}
Ext.define('Shopware.apps.NetiTags.controller.Overview', {
    extend: 'Shopware.apps.NetiFoundationExtensions.components.controller.Controller',

    createMainWindow: function () {
        var me = this;
        me.subApplication.getView('overview.Window').create();
    }
});
//{/block}
