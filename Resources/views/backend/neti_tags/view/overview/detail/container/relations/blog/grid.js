/**
 * @copyright  Copyright (c) 2017, Net Inventors GmbH
 * @category   NetiTags
 * @author     bmueller
 */
//{namespace name="plugins/neti_tags/backend/detail/relations/blog"}
//{block name="plugins/neti_tags/backend/view/overview/detail/container/relations/blog/grid"}
Ext.define('Shopware.apps.NetiTags.view.overview.detail.container.relations.blog.Grid', {
    'extend': 'Shopware.apps.NetiFoundationExtensions.components.view.grid.Association',
    'alias': 'widget.neti_tags_view_overview_detail_container_relations_blog_grid',
    'border': null,
    'searchComboDisplayField': 'title',
    'configure': function () {
        return {
            'controller': 'NetiTagsTag',
            'associationKey': 'blog',
            'columns': {
                'title': {
                    'header': '{s name="grid_column_title"}Title{/s}'
                }
            }
        };
    }
});
//{/block}
