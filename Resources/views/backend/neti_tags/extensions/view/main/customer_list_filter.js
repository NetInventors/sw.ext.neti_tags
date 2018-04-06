//{namespace name="plugins/neti_tags/backend/detail/relations/customer"}
// {block name="backend/customer/view/main/customer_list_filter"}
//{$smarty.block.parent}
Ext.override(Shopware.apps.Customer.view.main.CustomerListFilter, {
    'configure': function() {
        var me = this,
            configuration = me.callParent(arguments),
            factory = Ext.create('Shopware.attribute.SelectionFactory'),
            tagsStore = factory.createEntitySearchStore("NetiTags\\Models\\Tag");

        configuration.fields.tags = {
            'fieldLabel': '{s name="customer_list_filter_field_label"}Tags{/s}',
            'xtype': 'netipagingcombobox',
            'displayField': 'title',
            'valueField': 'id',
            'store': tagsStore
        };

        return configuration;
    }
});
//{/block}
