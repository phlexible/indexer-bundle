Ext.provide('Phlexible.Handles');
Ext.provide('Phlexible.gui.menuhandle.handle.XtypeHandle');
Ext.provide('Phlexible.indexer.MainPanel');

Phlexible.indexer.menuhandle.SearchHandle = Ext.extend(Phlexible.gui.menuhandle.handle.XtypeHandle, {

});

Phlexible.Handles.add('indexer', function() {
    return new Phlexible.gui.menuhandle.handle.XtypeHandle({
        text: Phlexible.indexer.Strings.search,
        iconCls: 'p-indexer-component-icon',
        component: 'indexer-mainpanel'
    });
});
