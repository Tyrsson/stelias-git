define([
    "dojo/query",
    "dojo/dom",
    "dojo/dom-style",
    "dojo/dom-class",
    "dojo/dom-construct",
    "dojo/dom-geometry",
    "dojo/string",
    "dojo/on",
    "dojo/aspect",
    "dojo/keys",
    "dojo/_base/config",
    "dojo/_base/lang",
    "dojo/_base/fx",
    "dijit/registry",
    "dojo/parser",
    "dijit/layout/ContentPane",
    "dojox/image/LightboxNano",
    "dijit/Tooltip",
    "aurora/tour/module"
], function(query, dom, domStyle, domClass, domConstruct, domGeometry, string, on, aspect, keys, config, lang, baseFx, registry, parser, ContentPane, ItemFileReadStore, LightboxNano, Tooltip) {

	createTab = function (location, itemTitle) {
		var contr = registry.byId("tabs");
		// create the new tab panel for this search
		var panel = new ContentPane({
			title: itemTitle,
			href:  location,
			closable: true
		});
		contr.addChild(panel);
		// make this tab selected
		contr.selectChild(panel);
	},
	startup = function() {
		query(".trigger").on("click", function(event) {
			event.preventDefault();
			var title = event.target.title;
			var href = event.target.href;
			createTab(href, title);
			//alert("Load Image");
		});
	};
	return {
		init: function() {
			//startLoading();

			// register callback for when dependencies have loaded
			startup();

		}
	};
});