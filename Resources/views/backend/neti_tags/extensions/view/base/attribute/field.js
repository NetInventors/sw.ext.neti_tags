//{block name="backend/base/attribute/form" append}
Ext.define('Shopware.apps.NetiTagsExtensions.view.base.attribute.tags.Field', {
    'extend': 'Ext.form.FieldContainer',
    'alias': 'widget.neti_tags_field',
    'layout': {
        'type': 'vbox',
        'align': 'stretch',
        'pack': 'start'
    },
    'mixins': {
        'field': 'Ext.form.field.Field'
    },
    'value': null,
    'allowBlank': true,
    'initComponent': function () {
        var me = this;

        me.items = [
            me.getGrid()
        ];

        me.callParent(arguments);
    },

    'getGrid': function () {
        var me = this;

        return me.grid || me.createGrid();
    },

    'createGrid': function () {
        var me = this;

        me.grid = Ext.create('Shopware.form.field.Grid', {
            'store': me.store,
            'flex': 1,
            'searchStore': me.searchStore
        });

        return me.grid;
    },

    'setValue': function (value) {
        var me = this;

        me.grid.setValue(value);
    },

    'isValid': function () {
        var me = this;

        if (false === me.allowBlank && 0 === me.grid.store.data.length) {
            return false;
        }

        return me.grid.isValid();
    },

    'getValue': function () {
        var me = this;

        return me.grid.getValue();
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
