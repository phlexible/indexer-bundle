Phlexible.indexer.MainPanel = Ext.extend(Ext.Panel, {
    title: Phlexible.strings.Indexer.indexer,
    iconCls: 'p-indexer-search-icon',
    strings: Phlexible.strings.Indexer,
    layout: 'border',

    initComponent: function() {
        this.store = new Ext.data.JsonStore({
            url: MWF.baseUrl + '/indexer/search',
            baseParams: {
                query: ''
            },
            fields: ['id', 'tid', 'eid', 'lang', 'content', 'online', 'title', 'node'],
            root: 'docs',
            totalProperty: 'total',
            listeners: {
                load: {
                    fn: function() {
                        this.getComponent(0).getComponent(0).getBottomToolbar().items.items[2].enable();
                    },
                    scope: this
                }
            }
        });

        this.items = [{
            region: 'north',
            layout: 'border',
            height: 140,
            border: false,
            items: [{
                xtype: 'form',
                title: this.strings.search,
                region: 'center',
                border: true,
                bodyStyle: 'padding: 5px',
                items: [{
                    xtype: 'textarea',
                    fieldLabel: this.strings.query,
                    width: 400,
                    allowEmpty: false
                }],
                bbar: [{
                    text: this.strings.search,
                    iconCls: 'p-indexer-search-icon',
                    handler: this.onSearch,
                    scope: this
                },'->',{
                    text: this.strings.save_check_query,
                    iconCls: 'p-indexer-save-icon',
                    disabled: true,
                    handler: this.onSaveCheckQuery,
                    scope: this
                }]
            },{
                region: 'east',
                title: this.strings.check,
                width: 400,
                border: true,
                collapsible: true,
                bodyStyle: 'padding: 5px;',
                items: [{
                    html: this.strings.current_query,
                    border: false
                },{
                    html: this.strings.result,
                    border: false,
                    hidden: true,
                    bodyStyle: 'padding-top: 10px;'
                }],
                bbar: [{
                    text: this.strings.check_now,
                    iconCls: 'p-indexer-check-icon',
                    disabled: true,
                    handler: this.loadCheck,
                    scope: this
                },'->',{
                    text: this.strings.show_results,
                    iconCls: 'p-indexer-search-icon',
                    disabled: true,
                    handler: this.onSearchCheckQuery,
                    scope: this
                }]
            }]
        },{
            xtype: 'grid',
            region: 'center',
            viewConfig: {
                emptyText: this.strings.no_results
            },
            store: this.store,
            autoExpandColumn: 4,
            columns: [{
                dataIndex: 'id',
                header: this.strings.id,
                width: 150
            },{
                dataIndex: 'tid',
                header: this.strings.tid,
                width: 60
            },{
                dataIndex: 'eid',
                header: this.strings.eid,
                width: 60
            },{
                dataIndex: 'lang',
                header: this.strings.language
            },{
                dataIndex: 'title',
                header: this.strings.title,
                width: 200
            },{
                dataIndex: 'online',
                header: this.strings.online,
                hidden: true,
                width: 60
            },{
                dataIndex: 'node',
                header: '_node', //this.strings.node,
                width: 200
            }],
            bbar: new Ext.PagingToolbar({
                store: this.store
            })
        }];

        this.on('render', this.loadCheck, this);

        Phlexible.indexer.MainPanel.superclass.initComponent.call(this);
    },

    loadCheck: function() {
        Ext.Ajax.request({
            url: MWF.baseUrl + '/indexer/check/check',
            success: function(response) {
                var data = Ext.decode(response.responseText);

                if (data.success || data.data.query) {
                    this.checkQuery = data.data.query;
                    this.getComponent(0).getComponent(1).getComponent(0).body.update(this.strings.current_query + ':<br />' + data.data.query);
                    this.getComponent(0).getComponent(1).getComponent(1).body.update(this.strings.result + ':<br />' + MWF.inlineIcon('p-indexer-' + (data.success ? 'ok' : 'nok') + '-icon') + ' ' + data.msg);
                    this.getComponent(0).getComponent(1).getComponent(1).show();

                    var tb = this.getComponent(0).getComponent(1).getBottomToolbar();
                    tb.items.items[0].enable();
                    tb.items.items[2].enable();
                }
                else if (!data.success) {
                    this.getComponent(0).getComponent(1).getComponent(0).body.update(MWF.inlineIcon('p-indexer-nok-icon') + ' ' + data.msg);
                    this.getComponent(0).getComponent(1).getComponent(1).body.update('');
                    this.getComponent(0).getComponent(1).getComponent(1).hide();

                    var tb = this.getComponent(0).getComponent(1).getBottomToolbar();
                    tb.items.items[0].disable();
                    tb.items.items[2].disable();
                }
            },
            failure: function(response) {
                var data = Ext.decode(response.responseText);

                this.getComponent(0).getComponent(1).getComponent(0).body.update(this.strings.current_query + ':<br />' + MWF.inlineIcon('p-indexer-nok-icon') + ' Error occured');
                this.getComponent(0).getComponent(1).getComponent(1).body.update('');
                this.getComponent(0).getComponent(1).getComponent(1).hide();

                var tb = this.getComponent(0).getComponent(1).getBottomToolbar();
                tb.items.items[0].disable();
                tb.items.items[2].disable();
            },
            scope: this
        });
    },

    onSearchCheckQuery: function() {
        this.getComponent(0).getComponent(0).getComponent(0).setValue(this.checkQuery);
        this.onSearch();
    },

    onSearch: function() {
        var query = this.getComponent(0).getComponent(0).getComponent(0).getValue();

        this.store.baseParams.query = query;
        this.store.load();
    },

    onSaveCheckQuery: function() {
        var query = this.getComponent(0).getComponent(0).getComponent(0).getValue();

        Ext.Ajax.request({
            url: MWF.baseUrl + '/indexer/check/set',
            params: {
                query: query
            },
            success: function(response) {
                var data = Ext.decode(response.responseText);

                if (data.success) {
                    MWF.success(data.msg);
                    this.loadCheck();
                }
                else {
                    Ext.MessageBox.alert('Failure', data.msg);
                    this.getComponent(0).getComponent(0).getBottomToolbar().items.items[2].disable();
                }
            },
            scope: this
        });
    }
});

Ext.reg('indexer-mainpanel', Phlexible.indexer.MainPanel);

