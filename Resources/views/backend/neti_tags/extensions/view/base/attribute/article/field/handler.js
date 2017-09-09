//{block name="backend/base/attribute/form" append}
Ext.define('Shopware.apps.NetiTagsExtensions.view.base.attribute.article.field.Handler', {
    'extend': 'Shopware.attribute.FieldHandlerInterface',
    'mixins': {
        'factory': 'Shopware.attribute.SelectionFactory'
    },
    'supports': function (attribute) {
        var columnName = attribute.get('columnName');
        if (attribute.get('tableName') !== 's_articles_attributes') {
            return false;
        }

        return (columnName === 'neti_tags');
    },

    'create': function (field, attribute) {
        console.log('field', field);
        return Ext.apply(field, {
            'xtype': 'neti_tags_article_attribute',
            'flex': 1,
            'store': this.createDynamicSearchStore(attribute),
            'searchStore': this.createDynamicSearchStore(attribute)
        });

        return this.createSelection(
            field,
            attribute,
            'Shopware.form.field.Grid',
            this.createDynamicSearchStore(attribute),
            this.createDynamicSearchStore(attribute)
        );
    }
});
//{/block}
