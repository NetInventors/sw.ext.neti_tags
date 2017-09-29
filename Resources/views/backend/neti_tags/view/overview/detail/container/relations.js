/**
 * @copyright  Copyright (c) 2017, Net Inventors GmbH
 * @category   NetiTags
 * @author     bmueller
 */
//{namespace name="plugins/neti_tags/backend/detail/relations"}
//{block name="plugins/neti_tags/backend/view/overview/detail/container/relations"}
Ext.define('Shopware.apps.NetiTags.view.overview.detail.container.Relations', {
    'extend': 'Ext.form.FieldContainer',
    'alias': 'widget.neti_tags_view_overview_detail_container_relations',
    'mixins': {
        'field': 'Ext.form.field.Field'
    },
    'border': null,
    'layout': 'fit',
    'snippets': {
        'tab_panel': {
            'article': {
                'title': '{s name="tab_panel_title_article"}Article{/s}'
            },
            'customer': {
                'title': '{s name="tab_panel_title_customer"}Customer{/s}'
            },
            'blog': {
                'title': '{s name="tab_panel_title_blog"}Blog{/s}'
            },
            'cms': {
                'title': '{s name="tab_panel_title_cms"}Cms{/s}'
            }
        }
    },
    'initComponent': function () {
        var me = this;

        me.stores = me.initStores();
        me.items = me.getItems();

        me.callParent(arguments);

        me.initField();
    },

    'getItems': function () {
        var me = this,
            items = [];

        items.push(
            me.getTabPanel()
        );

        return items;
    },

    'getTabPanel': function () {
        var me = this;

        return me.tabPanel || me.createTabPanel();
    },

    'createTabPanel': function () {
        var me = this;

        me.tabPanel = Ext.create('Ext.tab.Panel', {
            'border': null,
            'items': me.getTabPanelItems()
        });

        return me.tabPanel;
    },

    'getTabPanelItems': function () {
        var me = this,
            items = [];

        items.push(me.getArticleTabPanel());
        items.push(me.getCustomerTabPanel());
        items.push(me.getBlogTabPanel());
        items.push(me.getCmsTabPanel());

        return items;
    },

    'getArticleTabPanel': function () {
        var me = this;

        return me.articleTabPanel || me.createArticleTabPanel();
    },

    'getCustomerTabPanel': function () {
        var me = this;

        return me.customerTabPanel || me.createCustomerTabPanel();
    },

    'getBlogTabPanel': function () {
        var me = this;

        return me.blogTabPanel || me.createBlogTabPanel();
    },

    'getCmsTabPanel': function () {
        var me = this;

        return me.cmsTabPanel || me.createCmsTabPanel();
    },

    'createCmsTabPanel': function () {
        var me = this;

        me.cmsTabPanel = Ext.create('Ext.panel.Panel', {
            'title': me.snippets.tab_panel.cms.title,
            'border': null,
            'closable': false,
            'layout': 'fit',
            'items': me.getCmsTabPanelItems()
        });

        return me.cmsTabPanel;
    },

    'createBlogTabPanel': function () {
        var me = this;

        me.blogTabPanel = Ext.create('Ext.panel.Panel', {
            'title': me.snippets.tab_panel.blog.title,
            'border': null,
            'closable': false,
            'layout': 'fit',
            'items': me.getBlogTabPanelItems()
        });

        return me.blogTabPanel;
    },

    'createArticleTabPanel': function () {
        var me = this;

        me.articleTabPanel = Ext.create('Ext.panel.Panel', {
            'title': me.snippets.tab_panel.article.title,
            'border': null,
            'closable': false,
            'layout': 'fit',
            'items': me.getArticleTabPanelItems()
        });

        return me.articleTabPanel;
    },

    'createCustomerTabPanel': function () {
        var me = this;

        me.customerTabPanel = Ext.create('Ext.panel.Panel', {
            'title': me.snippets.tab_panel.customer.title,
            'border': null,
            'closable': false,
            'layout': 'fit',
            'items': me.getCustomerTabPanelItems()
        });

        return me.customerTabPanel;
    },

    'getArticleTabPanelItems': function () {
        var me = this,
            items = [];

        items.push(
            me.getArticleGrid()
        );

        return items;
    },

    'getCmsTabPanelItems': function () {
        var me = this,
            items = [];

        items.push(
            me.getCmsGrid()
        );

        return items;
    },

    'getBlogTabPanelItems': function () {
        var me = this,
            items = [];

        items.push(
            me.getBlogGrid()
        );

        return items;
    },

    'getCustomerTabPanelItems': function () {
        var me = this,
            items = [];

        items.push(
            me.getCustomerGrid()
        );

        return items;
    },

    'getCmsGrid': function () {
        var me = this;

        return me.cmsGrid || me.createCmsGrid();
    },

    'getBlogGrid': function () {
        var me = this;

        return me.blogGrid || me.createBlogGrid();
    },

    'getArticleGrid': function () {
        var me = this;

        return me.articleGrid || me.createArticleGrid();
    },

    'getCustomerGrid': function () {
        var me = this;

        return me.customerGrid || me.createCustomerGrid();
    },

    'createBlogGrid': function () {
        var me = this;

        me.blogGrid = Ext.create('Shopware.apps.NetiTags.view.overview.detail.container.relations.blog.Grid', {
            'store': me.getStore('blog')
        });

        return me.blogGrid;
    },

    'createCmsGrid': function () {
        var me = this;

        me.cmsGrid = Ext.create('Shopware.apps.NetiTags.view.overview.detail.container.relations.cms.Grid', {
            'store': me.getStore('cms')
        });

        return me.cmsGrid;
    },

    'createArticleGrid': function () {
        var me = this;

        me.articleGrid = Ext.create('Shopware.apps.NetiTags.view.overview.detail.container.relations.article.Grid', {
            'store': me.getStore('articles')
        });

        return me.articleGrid;
    },

    'createCustomerGrid': function () {
        var me = this;

        me.customerGrid = Ext.create('Shopware.apps.NetiTags.view.overview.detail.container.relations.customer.Grid', {
            'store': me.getStore('customers')
        });

        return me.customerGrid;
    },

    'getStore': function (name) {
        var me = this;

        if (!me.stores.hasOwnProperty(name)) {
            me.stores[name] = me.createStore(name);
        }

        return me.stores[name];
    },

    'getStores': function () {
        var me = this;

        return me.stores;
    },

    'createStore': function (name) {
        var me = this,
            store;

        switch (name) {
            case 'articles':
                store = me.subApp.getStore('relations.Article');
                break;
            case 'customers':
                store = me.subApp.getStore('relations.Customer');
                break;
            case 'blog':
                store = me.subApp.getStore('relations.Blog');
                break;
            case 'cms':
                store = me.subApp.getStore('relations.Cms');
                break;
        }

        return store;
    },

    'initStores': function () {
        var me = this,
            stores = {};

        return stores;
    },

    'setValue': function (value) {
        var me = this,
            stores = me.getStores();

        me.clearStores(stores);

        if (Ext.isObject(value)) {
            Ext.Object.each(stores, function (key, store) {
                if (value.hasOwnProperty(key)) {
                    me.setStoreValue(value[key], store);
                }
            });
        }
    },

    'clearStores': function (stores) {
        Ext.Object.each(stores, function (key, store) {
            store.removeAll();
        });
    },

    'setStoreValue': function (values, store) {
        var me = this,
            addValues = [];

        if (Ext.isArray(values)) {
            if (store.data.items.length > 0) {
                store.addListener(
                    'clear',
                    function () {
                        Ext.each(values, function (item) {
                            if (item.isModel) {
                                addValues.push(item.getData());
                            } else {
                                addValues.push(item);
                            }
                        });
                        store.add(addValues);
                    },
                    store,
                    {
                        'single': true
                    }
                );

                store.removeAll();
            } else {
                Ext.each(values, function (item) {
                    if (item.isModel) {
                        addValues.push(item.getData());
                    } else {
                        addValues.push(item);
                    }
                });
                store.add(addValues);
            }
        }
    },

    'getValue': function () {
        var me = this,
            values = {},
            stores = me.getStores();

        Ext.Object.each(stores, function (key, store) {
            var items = [];
            if (store.data) {
                store.each(function (item) {
                    items.push(item.getData());
                });
                values[key] = items;
            }
        });

        return values;
    },

    'isValid': function () {
        return true;
    },

    'getSubmitValue': function () {
        var me = this;

        return me.getValue();
    },

    'getSubmitData': function () {
        var me = this,
            result = {};

        result[me.getName()] = me.getValue();

        return result;
    },

    'getModelData': function () {
        var me = this;

        return me.getValue();
    }
});
//{/block}
