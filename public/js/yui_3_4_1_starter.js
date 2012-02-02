var myUrl=document.URL;
myUrl=myUrl.substring(0,myUrl.indexOf("/",7));
YUI_config = {
        comboBase: myUrl + "/combo?",
        root: "yui/yui_3.4.1/build/",
        base: myUrl + "/yui/yui_3.4.1/build/",
        combine: true,
        groups:{
		    gallery: {
		        combine:true,
		        base: myUrl + "/yui/yui-yui3-gallery-5cc7ad7/build/",
		        root: "yui/yui-yui3-gallery-5cc7ad7/build/",
		        comboBase: myUrl + "/combo.action?",
		        patterns: {
		            "gallery-":    {},
		            "gallerycss-": { type: "css" }
		        }   
		    },
		    gallerycss: {
		        base: myUrl + "/yui/yui-yui3-gallery-5cc7ad7/build/",
		        root: "yui/yui-yui3-gallery-5cc7ad7/build/",
		        comboBase: myUrl + "/combo.action?",
                modules:   {
		    		"gallery-yui3treeview-css": {
		                path: "gallery-yui3treeview/assets/treeview-classic.css",
		                type: "css"
		            },  
		    		"gallery-accordion-css": {
                        path: "gallery-accordion/assets/skins/sam/gallery-accordion.css",
                        type: "css"
                    },  
                    "gallery-treeviewlite-core-css": {
                        path: "gallery-treeviewlite/assets/gallery-treeviewlite-core.css",
                        type: "css"
                    },  
                    "gallery-treeviewlite-skin-css": {
                        path: "gallery-treeviewlite/assets/skins/sam/gallery-treeviewlite-skin.css",
                        type: "css"
                    }   
                }   
            },
            
            yui2: {
		        combine: true,
		        base: myUrl + "/yui/yui-2in3-5a01c0b/2.9.0/build/",
		        root: "yui/yui-2in3-5a01c0b/2.9.0/build/",
		        comboBase: myUrl + "/combo.action?",
                patterns:  {
                    "yui2-": {
                        configFn: function (me) {
                            if(/-skin|reset|fonts|grids|base/.test(me.name)) {
                                me.type = "css";
                                me.path = me.path.replace(/\.js/, ".css");
                                me.path = me.path.replace(/\/yui2-skin/, "/assets/skins/sam/yui2-skin");
                                //alert(me.path);
                            }   
                        }   
                    }   
                }                
            }		    
		}
    };
