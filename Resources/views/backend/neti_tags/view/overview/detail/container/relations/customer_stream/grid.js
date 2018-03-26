/**
 * @copyright  Copyright (c) 2017, Net Inventors GmbH
 * @category   NetiTags
 * @author     bmueller
 */
//{namespace name="plugins/neti_tags/backend/detail/relations/customer_stream"}
//{block name="plugins/neti_tags/backend/view/overview/detail/container/relations/customer_stream/grid"}
Ext.define('Shopware.apps.NetiTags.view.overview.detail.container.relations.customerStream.Grid', {
    'extend': 'Shopware.apps.NetiFoundationExtensions.components.view.grid.Association',
    'alias': 'widget.neti_tags_view_overview_detail_container_relations_customer_stream_grid',
    'border': null,
    'configure': function () {
        return {
            'controller': 'NetiTagsTag',
            'associationKey': 'customer_stream',
            'columns': {
                'name': {
                    'header': '{s name="grid_column_name"}Name{/s}'
                }
            }
        };
    }
});
//{/block}
