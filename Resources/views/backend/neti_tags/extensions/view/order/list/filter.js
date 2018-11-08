//{namespace name="plugins/neti_tags/backend/detail/relations/order"}
//{block name="backend/order/view/list/filter"}
//{$smarty.block.parent}
Ext.override(Shopware.apps.Order.view.list.Filter, {
    'createFilterForm': function () {
        var me = this,
            form = me.callParent(arguments);

        form.add(
            me.createTagsSelection()
        );

        return form;
    },

    'createTagsSelection': function () {
        var selectionFactory = Ext.create('Shopware.attribute.SelectionFactory', {});
        return Ext.create('Ext.form.field.ComboBox', {
            'name': 'attribute.netiTags',
            'store': selectionFactory.createEntitySearchStore("NetiTags\\Models\\Tag"),
            'valueField': 'id',
            'queryMode': 'remote',
            'displayField': 'title',
            'fieldLabel': '{s name="filter_field_label"}Tags{/s}'
        });
    }
});
//{/block}
