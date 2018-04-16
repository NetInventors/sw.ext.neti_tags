/**
 * @copyright  Copyright (c) 2017, Net Inventors GmbH
 * @category   Shopware
 * @author     dpogodda
 */

//{namespace name="plugins/neti_tags/backend/overview"}
//{block name="plugins/neti_tags/backend/controller/overview_grid"}
Ext.define('Shopware.apps.NetiTags.controller.OverviewGrid', {
    extend: 'Shopware.grid.Controller',
    'init': function() {
        var me = this;

        me.callParent(arguments);

        me.deleteConfirmTextOriginal = me.deleteConfirmText;
    },

    onDeleteItem: function (grid, record) {
        var me = this,
            relationCount = record.get('relationCount'),
            deleteprotecting = Shopware.apps.NetiFoundationExtensions.Helper.getPluginConfig(
            'Shopware.apps.NetiTags',
            'deleteprotecting'
        );

        if(false === deleteprotecting) {
            if (relationCount > 0) {
                me.deleteConfirmText = '{s name="grid_controller/delete_confirm_text"}Attention, this tag is linked to at least one more element, really delete?{/s}';
            } else {
                me.deleteConfirmText = me.deleteConfirmTextOriginal;
            }
        } else {
            if (relationCount > 0) {
                Ext.MessageBox.alert(
                    me.deleteConfirmTitle,
                    '{s name="grid_controller/deleteprotecting_enabled_text"}Attention, this tag is linked to at least one more element, and therefore cannot delete!{/s}'
                );

                return;
            }
        }

        me.parentOnDeleteItem(grid, record);
    },

    parentOnDeleteItem: function (grid, record) {
        var me = this,
            text = me.deleteConfirmText,
            title = me.deleteConfirmTitle;

        if (grid.getConfig('displayProgressOnSingleDelete')) {
            me.onDeleteItems(grid, [ record ], null);
            return;
        }

        Ext.MessageBox.confirm(title, text, function (response) {
            if (response !== 'yes') {
                return false;
            }

            if (!me.hasModelAction(record, 'destroy')) {
                grid.getStore().remove(record);
                return true;
            }

            record.destroy({
                success: function (result, operation) {
                    grid.getStore().load();
                },
                failure: function (result, operation) {
                    Shopware.Notification.createGrowlMessage(
                        me.deleteConfirmTitle,
                        '{s name="grid_controller/deleteprotecting_enabled_text"}Attention, this tag is linked to at least one more element, and therefore cannot delete!{/s}',
                        'NetiTags'
                    );
                }
            });
        });
    }
});
//{/block}
