/**
 * @copyright  Copyright (c) 2017, Net Inventors GmbH
 * @category   NetiTags
 * @author     bmueller
 */
//{namespace name="plugins/neti_tags/backend/detail/relations/cms"}
//{block name="plugins/neti_tags/backend/view/overview/detail/container/relations/cms/grid"}
Ext.define('Shopware.apps.NetiTags.view.overview.detail.container.relations.cms.Grid', {
    'extend': 'Shopware.apps.NetiFoundationExtensions.components.view.grid.Association',
    'alias': 'widget.neti_tags_view_overview_detail_container_relations_cms_grid',
    'border': null,
    'searchComboDisplayField': 'description',
    'configure': function () {
        return {
            'controller': 'NetiTagsTag',
            'associationKey': 'cms',
            'columns': {
                'description': {
                    'header': '{s name="grid_column_description"}Description{/s}'
                }
            }
        };
    }
});
//{/block}
