/**
 * @copyright  Copyright (c) 2017, Net Inventors GmbH
 * @category   NetiTags
 * @author     bmueller
 */
//{namespace name="plugins/neti_tags/backend/detail/relations/order"}
//{block name="plugins/neti_tags/backend/view/overview/detail/container/relations/order/grid"}
Ext.define('Shopware.apps.NetiTags.view.overview.detail.container.relations.order.Grid', {
    'extend': 'Shopware.apps.NetiFoundationExtensions.components.view.grid.Association',
    'alias': 'widget.neti_tags_view_overview_detail_container_relations_order_grid',
    'searchComboDisplayField': 'number',
    'border': null,
    'configure': function () {
        var me = this;
        return {
            'controller': 'NetiTagsTag',
            'associationKey': 'order',
            'columns': {
                'orderTime': {
                    'header': '{s name="grid_column_order_time"}Order time{/s}',
                    'flex': 1,
                    'renderer': me.dateColumn
                },
                'number': {
                    'header': '{s name="grid_column_number"}Number{/s}',
                    'flex': 1
                },
                'invoiceAmount': {
                    'header': '{s name="grid_column_invoice_amount"}Invoice amount{/s}',
                    'flex': 1,
                    'renderer': me.amountColumn
                },
                'customerId': {
                    'header': '{s name="grid_column_customer"}Customer{/s}',
                    'flex': 3,
                    'align': 'left',
                    'renderer': me.customerColumn,
                    'getSortParam': function () {
                        return 'customerName';
                    }
                },
                'customerEmail': {
                    'header': '{s name="grid_column_email"}Email{/s}',
                    'flex': 2,
                    'renderer': me.customerEmailColumn
                }
            }
        };
    },

    'createActionColumnItems': function () {
        var me = this,
            items = me.callParent(arguments);

        items.unshift(me.createOpenOrderColumn());

        return items;
    },

    'createOpenOrderColumn': function () {
        var column;

        column = {
            'action': 'order',
            'iconCls': 'sprite-sticky-notes-pin customers--orders',
            'tooltip': '{s name="grid_tool_tip_order"}Show order{/s}',
            'handler': function (view, rowIndex, colIndex, item, opts, record) {
                Shopware.app.Application.addSubApplication({
                    'name': 'Shopware.apps.Order',
                    'action': 'detail',
                    'params': {
                        'orderId': record.get('id')
                    }
                });
            }
        };

        return column;
    },

    'dateColumn': function (value, metaData, record) {
        if (value === Ext.undefined) {
            return value;
        }

        return Ext.util.Format.date(value) + ' ' + Ext.util.Format.date(value, timeFormat);
    },

    'customerColumn': function (value, metaData, record, colIndex, store, view) {
        var name = Ext.String.trim(
            record.get('lastName') + ', ' + record.get('firstName')
        );

        if (record.get('company').length > 0) {
            name += ' (' + record.get('company') + ')';
        }

        return name;
    },

    'amountColumn': function (value, metaData, record) {
        if (value === Ext.undefined) {
            return value;
        }
        return Ext.util.Format.currency(value);
    },

    'customerEmailColumn': function (value) {
        return (Ext.isDefined(value)) ? Ext.String.format('<a href="mailto:[0]" data-qtip="[0]">[0]</a>', value) : value;
    }
});
//{/block}