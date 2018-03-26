/**
 * @copyright  Copyright (c) 2017, Net Inventors GmbH
 * @category   Shopware
 * @author     dpogodda
 */

/**
 * glob: Ext, Shopware
 */
//{block name="plugins/neti_tags/backend/application"}
Ext.define('Shopware.apps.NetiTags', {
    name: 'Shopware.apps.NetiTags',
    extend: 'Enlight.app.SubApplication',
    loadPath: '{url action=load}',
    bulkLoad: true,

    controllers: [
        'Main'
    ],

    models: [
        'Tag',
        'relations.Blog',
        'relations.Cms',
        'relations.Article',
        'relations.Category',
        'relations.Customer',
        'relations.CustomerStream',
        'relations.ProductStream'
    ],

    views: [
        'overview.List',
        'overview.Window',
        'overview.detail.container.relations.article.Grid',
        'overview.detail.container.relations.blog.Grid',
        'overview.detail.container.relations.cms.Grid',
        'overview.detail.container.relations.customer.Grid',
        'overview.detail.container.relations.category.Grid',
        'overview.detail.container.relations.productStream.Grid',
        'overview.detail.container.relations.customerStream.Grid',
        'overview.detail.container.Relations',
        'overview.detail.Container',
        'overview.detail.Window'
    ],

    stores: [
        'Tag',
        'relations.Blog',
        'relations.Cms',
        'relations.Article',
        'relations.Category',
        'relations.Customer',
        'relations.CustomerStream',
        'relations.ProductStream'
    ],

    launch: function() {
        var me = this;

        me.getController('Main');
    }
});
//{/block}
