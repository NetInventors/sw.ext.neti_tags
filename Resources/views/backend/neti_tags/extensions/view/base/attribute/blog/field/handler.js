//{block name="backend/base/attribute/form" append}
Ext.define('Shopware.apps.NetiTagsExtensions.view.base.attribute.blog.field.Handler', {
    'extend': 'Shopware.attribute.FieldHandlerInterface',
    'mixins': {
        'factory': 'Shopware.attribute.SelectionFactory'
    },
    'supports': function (attribute) {
        var columnName = attribute.get('columnName');
        if (attribute.get('tableName') !== 's_blog_attributes') {
            return false;
        }

        return (columnName === 'neti_tags');
    },

    'create': function (field, attribute) {
        return Ext.apply(field, {
            'xtype': 'neti_tags_blog_attribute',
            'flex': 1,
            'store': this.createDynamicSearchStore(attribute),
            'searchStore': this.createDynamicSearchStore(attribute)
        });
    }
});
//{/block}
