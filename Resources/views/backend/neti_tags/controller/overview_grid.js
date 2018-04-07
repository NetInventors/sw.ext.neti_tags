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

        // if(true === me.deletprotecting) {
        //     me.deleteConfirmText = '{s name="grid_controller/delete_confirm_text"}Attention, this tag is linked to at least one more element, really delete?{/s}';
        // }
    },

    onDeleteItem: function (grid, record) {
        var me = this,
            relationCount = record.get('relationCount'),
            deletprotecting = Shopware.apps.NetiFoundationExtensions.Helper.getPluginConfig(
            'Shopware.apps.NetiTags',
            'deletprotecting'
        );

        if(false === deletprotecting) {
            if (relationCount > 0) {
                me.deleteConfirmText = '{s name="grid_controller/delete_confirm_text"}Attention, this tag is linked to at least one more element, really delete?{/s}';
            } else {
                me.deleteConfirmText = me.deleteConfirmTextOriginal;
            }
        } else {
            if (relationCount > 0) {
                Ext.MessageBox.alert(
                    me.deleteConfirmTitle,
                    '{s name="grid_controller/deletprotecting_enabled_text"}Attention, this tag is linked to at least one more element, and therefore cannot delete!{/s}'
                );

                return;
            }
        }

        me.callParent(arguments);
    }
});
//{/block}
