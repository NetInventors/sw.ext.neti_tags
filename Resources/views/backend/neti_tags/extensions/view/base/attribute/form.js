//{block name="backend/base/attribute/form" append}
Ext.override(Shopware.attribute.Form, {
    'registerTypeHandlers': function () {
        var handlers = this.callParent(arguments);

        return Ext.Array.insert(handlers, 0, [
            Ext.create('Shopware.apps.NetiTagsExtensions.view.base.attribute.article.field.Handler')
        ]);
    }
});
//{/block}
