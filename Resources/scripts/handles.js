Ext.require('Phlexible.Handles');
Ext.require('Phlexible.gui.menuhandle.handle.XtypeHandle');
Ext.require('Phlexible.indexer.MainPanel');

Phlexible.Handles.add('indexer', function() {
    return new Phlexible.gui.menuhandle.handle.XtypeHandle({
        text: Phlexible.indexer.Strings.search,
        iconCls: 'p-indexer-component-icon',
        component: 'indexer-mainpanel'
    });
});
