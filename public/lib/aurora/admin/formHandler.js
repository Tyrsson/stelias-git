define([
    "dojo/request/xhr", 
    "dojo/request/notify",
    "dojo/query",
    "dojo/dom",
    "dojo/dom-style",
    "dojo/dom-class",
    "dojo/dom-construct",
    "dojo/dom-geometry",
    "dojox/form/Manager",
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
    "dijit/Tooltip",
    "aurora/admin/module"
], function(xhr, notify, query, dom, domStyle, domClass, domConstruct, domGeometry, Manager, string, on, aspect, keys, config, lang, baseFx, registry, parser, ContentPane, Tooltip) {

	connect = function(fm) {
		
		//var form = registry.byId("form");
		
		console.log(fm);
		
		on(fm, "submit", function(evt){
			evt.preventDefault();
			evt.stopPropagation();
			//console.log("Submit");
			xhrArgs = {
					form: form,
					handleAs: "text"
			};
			console.log(xhrArgs);
			xhr(xhrArgs).then(function(data){
			      console.log("response returned");
			    });
			
		});		
		// ensure the submit buttons don't submit, but log
//		form.on("submit", function(evt){
//			evt.preventDefault();
//			evt.stopPropagation();
//			console.log("Submit");
//		});
		
		
//		if(registry.byId("form") !== "undefined") {
//			alert("form found");
//		}
		//alert("running..");
//		query(".trigger").on("click", function(event) {
//			event.preventDefault();
//			var title = event.target.title;
//			var href = event.target.href;
//			createTab(href, title);
//			//alert("Load Image");
//		});
	};
	return {
		init: function() {
			connect(fm);
		}
	};
});