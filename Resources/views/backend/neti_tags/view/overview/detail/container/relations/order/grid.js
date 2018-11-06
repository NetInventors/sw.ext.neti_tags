/**
 * @copyright  Copyright (c) 2017, Net Inventors GmbH
 * @category   NetiTags
 * @author     bmueller
 */
//{namespace name="plugins/neti_tags/backend/detail/relations/order"}
//{block name="plugins/neti_tags/backend/view/overview/detail/container/relations/order/grid"}
Ext.define('Shopware.apps.NetiTags.view.overview.detail.container.relations.order.Grid', {
    'extend': 'Shopware.apps.NetiFoundationExtensions.components.view.grid.Association',
    'alias': 'widget.neti_tags_view_overview_detail_container_relations_order_grid',
    'searchComboDisplayField': 'number',
    'border': null,
    'configure': function () {
        return {
            'controller': 'NetiTagsTag',
            'associationKey': 'order',
            'columns': {
                'number': {
                    'header': '{s name="grid_column_number"}Number{/s}'
                }
            }
        };
    }
});
//{/block}
