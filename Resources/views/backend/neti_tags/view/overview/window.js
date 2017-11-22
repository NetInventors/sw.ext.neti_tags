/**
 * @copyright  Copyright (c) 2017, Net Inventors GmbH
 * @category   Shopware
 * @author     dpogodda
 */

//{namespace name="plugins/neti_tags/backend/overview"}
//{block name="plugins/neti_tags/backend/view/overview/window"}
Ext.define('Shopware.apps.NetiTags.view.overview.Window', {
    extend: 'Shopware.window.Listing',
    alias: 'widget.neti_tags_view_overview_window',
    title: '{s name="plugin_name" namespace="plugins/neti_tags/neti_tags"}Tags{/s}{s name="separator" namespace="plugins/neti_tags/neti_tags"} > {/s}{s name="window_title"}Overview{/s}',
    autoShow: true,
    configure: function () {
        return {
            'listingGrid': 'Shopware.apps.NetiTags.view.overview.List',
            'listingStore': 'Shopware.apps.NetiTags.store.Tag'
        };
    }
});
//{/block}