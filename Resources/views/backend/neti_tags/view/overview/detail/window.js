/**
 * @copyright  Copyright (c) 2017, Net Inventors GmbH
 * @category   Shopware
 * @author     dpogodda
 */

//{namespace name="plugins/neti_tags/backend/detail/detail"}
//{block name="plugins/neti_tags/backend/view/overview/detail/window"}
Ext.define('Shopware.apps.NetiTags.view.overview.detail.Window', {
    extend: 'Shopware.apps.NetiFoundationExtensions.components.view.window.Detail',
    alias: 'widget.neti_tags_view_overview_detail_window',
    title: '{s name="plugin_name" namespace="plugins/neti_tags/neti_tags"}NetiTags{/s}{s name="separator" namespace="plugins/neti_tags/neti_tags"} > {/s}{s name=window_title}Detail{/s}',
    initComponent: function () {
        var me = this;

        me.callParent(arguments);

        Shopware.app.Application.on(me.eventAlias + '-save-successfully', function () {
            me.destroy();
        });
    }
});
//{/block}