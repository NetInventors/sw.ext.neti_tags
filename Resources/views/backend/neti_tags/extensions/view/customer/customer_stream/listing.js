// {block name="backend/customer/view/customer_stream/listing"}
//{$smarty.block.parent}
Ext.override(Shopware.apps.Customer.view.customer_stream.Listing, {
    'createPlugins': function() {
        var plugins = this.callParent(arguments);

        Ext.each(plugins, function(plugin) {
            if('grid-attributes' === plugin.ptype) {
                plugin.createActionColumn = false;
            }
        });

        return plugins;
    },

    'createActionColumnItems': function () {
        var me = this,
            items = me.callParent(arguments);

        items.push({
            iconCls: 'sprite-attributes',
            name: 'grid-attribute-plugin',
            handler: function (view, rowIndex, colIndex, item, opts, record) {
                me.attributeActionColumnClick(record);
            },
            getClass: me.attributeColumnRenderer,
            scope: me
        });

        return items;
    },

    attributeColumnRenderer: function(value, meta, record) {
        if (!record.get('id') || !this.backendAttributes || this.backendAttributes.length <= 0) {
            return 'x-hidden';
        }
    },

    attributeActionColumnClick: function(record) {
        var me = this;

        me.attributeWindow = Ext.create('Shopware.attribute.Window', {
            table: 's_customer_streams_attributes',
            record: record
        });
        me.attributeWindow.show();
    }
});
//{/block}
