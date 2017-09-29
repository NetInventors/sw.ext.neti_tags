/**
 * @copyright  Copyright (c) 2017, Net Inventors GmbH
 * @category   NetiTags
 * @author     bmueller
 */
//{namespace name="plugins/neti_tags/backend/detail/relations/customer"}
//{block name="plugins/neti_tags/backend/view/overview/detail/container/relations/customer/grid"}
Ext.define('Shopware.apps.NetiTags.view.overview.detail.container.relations.customer.Grid', {
    'extend': 'Shopware.apps.NetiFoundationExtensions.components.view.grid.Association',
    'alias': 'widget.neti_tags_view_overview_detail_container_relations_customer_grid',
    'border': null,
    'configure': function () {
        return {
            'controller': 'NetiTagsTag',
            'associationKey': 'customers',
            'columns': {
                'name': {
                    'header': '{s name="grid_column_name"}Name{/s}'
                },
                'email': {
                    'header': '{s name="grid_column_email"}Email{/s}'
                }
            }
        };
    }
});
//{/block}
