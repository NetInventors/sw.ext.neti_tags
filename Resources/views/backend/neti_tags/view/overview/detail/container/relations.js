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

        return items;
    },

    'getArticleTabPanel': function () {
        var me = this;

        return me.articleTabPanel || me.createArticleTabPanel();
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

    'getArticleTabPanelItems': function () {
        var me = this,
            items = [];

        items.push(
            me.getArticleGrid()
        );

        return items;
    },

    'getArticleGrid': function () {
        var me = this;

        return me.articleGrid || me.createArticleGrid();
    },

    'createArticleGrid': function () {
        var me = this;

        me.articleGrid = Ext.create('Shopware.apps.NetiTags.view.overview.detail.container.relations.article.Grid', {
            'store': me.getStore('articles')
        });

        return me.articleGrid;
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

        if (Ext.isObject(value)) {
            Ext.Object.each(stores, function (key, store) {
                if (value.hasOwnProperty(key)) {
                    me.setStoreValue(value[key], store);
                }
            });
        }
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
