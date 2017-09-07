/**
 * @copyright  Copyright (c) 2017, Net Inventors GmbH
 * @category   Shopware
 * @author     dpogodda
 */

//{namespace name="plugins/neti_tags/backend/overview"}
//{block name="plugins/neti_tags/backend/view/overview/list"}
Ext.define('Shopware.apps.NetiTags.view.overview.List', {
    extend: 'Shopware.grid.Panel',
    alias: 'widget.neti_tags_view_overview_list',
    region: 'center',

    configure: function () {
        return {
            detailWindow: 'Shopware.apps.NetiTags.view.overview.detail.Window',
            columns: {
                title: {
                    header: '{s name="grid_column_title"}Tag{/s}'
                },
                // 'relations': {
                //     'header': '{s name="grid_column_relations"}Relations{/s}'
                // },
                disabled: {
                    header: '{s name="grid_column_disabled"}Disabled{/s}'
                }
            }
        };
    }
});
//{/block}
