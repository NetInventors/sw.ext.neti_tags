/**
 * @copyright  Copyright (c) 2017, Net Inventors GmbH
 * @category   Shopware
 * @author     dpogodda
 */

//{namespace name="plugins/neti_tags/backend/overview"}
//{block name="plugins/neti_tags/backend/controller/overview"}
Ext.define('Shopware.apps.NetiTags.controller.Overview', {
    extend: 'Shopware.apps.NetiFoundationExtensions.components.controller.Controller',

    //{literal}
    'titlePattern': '{0} ({1}, {2})',
    //{/literal}

    'init': function () {
        var me = this;

        me.control({
            'neti_tags_view_overview_detail_window': {
                'tag-after-load-record': me.onAfterLoadRecord
            }
        });

        me.callParent(arguments);
    },

    'onAfterLoadRecord': function(win, record) {
        var me = this,
            newTitle = Ext.String.format(
                me.titlePattern,
                win.title,
                record.get('title'),
                record.get('id')
            );

        win.setTitle(newTitle)
    },

    createMainWindow: function () {
        var me = this;
        me.subApplication.getView('overview.Window').create();
    }
});
//{/block}
