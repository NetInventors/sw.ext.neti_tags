/**
 * @copyright  Copyright (c) 2017, Net Inventors GmbH
 * @category   NetiTags
 * @author     bmueller
 */
//{namespace name="plugins/neti_tags/backend/detail/relations/category"}
//{block name="plugins/neti_tags/backend/view/overview/detail/container/relations/category/grid"}
Ext.define('Shopware.apps.NetiTags.view.overview.detail.container.relations.category.Grid', {
    'extend': 'Shopware.apps.NetiFoundationExtensions.components.view.grid.Association',
    'alias': 'widget.neti_tags_view_overview_detail_container_relations_category_grid',
    'border': null,
    'configure': function () {
        return {
            'controller': 'NetiTagsTag',
            'associationKey': 'categories',
            'columns': {
                'name': {
                    'header': '{s name="grid_column_name"}Name{/s}'
                }
            }
        };
    }
});
//{/block}
