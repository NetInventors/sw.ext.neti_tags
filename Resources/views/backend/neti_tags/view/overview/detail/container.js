/**
 * @copyright  Copyright (c) 2017, Net Inventors GmbH
 * @category   Shopware
 * @author     dpogodda
 */

//{namespace name="plugins/neti_tags/backend/detail"}
//{block name="plugins/neti_tags/backend/view/overview/detail/container"}
Ext.define('Shopware.apps.NetiTags.view.overview.detail.Container', {
    extend: 'Shopware.apps.NetiFoundationExtensions.components.model.Container',
    alias: 'widget.neti_tags_view_overview_detail_container',
    padding: 20,
    configure: function () {
        return {
            controller: 'NetiTagsTag',
            // associations: ['categories', 'articles'],
            fieldSets: [
                {
                    title: '{s name="field_set_title_general_settings"}General Settings{/s}',
                    columns: 1,
                    fields: {
                        title: {
                            fieldLabel: '{s name="field_label_title"}Tag{/s}',
                            helpText: '{s name="help_text_title"}{/s}',
                            allowBlank: false
                        },
                        description: {
                            fieldLabel: '{s name="field_label_description"}Description{/s}',
                            helpText: '{s name="help_text_description"}{/s}',
                            allowBlank: true
                        },
                        disabled: {
                            fieldLabel: '{s name="field_label_disabled"}Disabled{/s}',
                            helpText: '{s name="help_text_disabled"}{/s}',
                            allowBlank: false
                        }
                    }
                },
                {
                    title: '{s name="field_set_title_relations"}Relations{/s}',
                    columns: 1,
                    fields: {
                        relations: {
                            'xtype': 'neti_tags_view_overview_detail_container_relations',
                            'height': 300,
                            fieldLabel: '',
                            hideLabel: true,
                            allowBlank: true
                        }
                    }
                }
            ]
        };
    }
});
//{/block}
