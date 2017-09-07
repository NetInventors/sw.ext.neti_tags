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
        'relations.Article'
    ],

    views: [
        'overview.List',
        'overview.Window',
        'overview.detail.container.relations.article.Grid',
        'overview.detail.container.Relations',
        'overview.detail.Container',
        'overview.detail.Window'
    ],

    stores: [
        'Tag',
        'relations.Article'
    ],

    launch: function() {
        var me = this;

        me.getController('Main');
    }
});
//{/block}
