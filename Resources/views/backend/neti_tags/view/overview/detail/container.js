/**
 * @copyright  Copyright (c) 2017, Net Inventors GmbH
 * @category   Shopware
 * @author     dpogodda
 */

//{namespace name="plugins/neti_tags/backend/detail/detail"}
//{block name="plugins/neti_tags/backend/view/overview/detail/container"}
Ext.define('Shopware.apps.NetiTags.view.overview.detail.Container', {
    extend: 'Shopware.apps.NetiFoundationExtensions.components.model.Container',
    alias: 'widget.neti_tags_view_overview_detail_container',
    padding: 20,
    configure: function () {
        return {
            controller: 'NetiTagsTag',
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
                            xtype: 'textareafield',
                            fieldLabel: '{s name="field_label_description"}Description{/s}',
                            helpText: '{s name="help_text_description"}{/s}',
                            allowBlank: true
                        },
                        enabled: {
                            fieldLabel: '{s name="field_label_enabled"}Enabled{/s}',
                            helpText: '{s name="help_text_enabled"}{/s}',
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
