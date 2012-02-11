YUI.add("gallery-formmgr",function(d){function j(n,m){m=m||{};j.superclass.constructor.call(this,m);this.form_name=n;this.status_node=d.one(m.status_node);this.enabled=true;this.default_value_map=m.default_value_map;this.validation={fn:{},regex:{}};this.validation_msgs={};this.has_messages=false;this.has_errors=false;this.button_list=[];this.user_button_list=[];this.has_file_inputs=false;}var e="(?:^|\\s)(?:";var h=")(?:\\s|$)";j.row_marker_class="formmgr-row";j.field_marker_class="formmgr-field";j.status_marker_class="formmgr-message-text";j.status_none_class="formmgr-status-hidden";j.status_success_class="formmgr-status-success";j.status_failure_class="formmgr-status-failure";j.row_status_prefix="formmgr-has";var g;var l;var k;function b(){if(!g){g=j.status_success_class+"|"+j.status_failure_class;}return g;}function c(){if(!l){l=j.row_status_prefix+"([^\\s]+)";}return l;}function a(){if(!k){k=new RegExp(e+c()+h);}return k;}j.getElementStatus=function(o){var n=d.one(o).get("className").match(a());return(n&&n.length>1?n[1]:false);};function f(m){if(d.Lang.isString(m)){return m.replace(/^#/,"");}else{if(m instanceof d.Node){return m.get("id");}else{return m.id;}}}function i(){var q=(this.button_list.length===0);for(var p=0;p<this.form.elements.length;p++){var t=this.form.elements[p];var o=t.tagName.toLowerCase();var r=(t.type?t.type.toLowerCase():null);if(q&&(r=="submit"||r=="reset"||o=="button")){this.button_list.push(t);}if(!t.name){continue;}var m=this.default_value_map[t.name];if(o=="input"&&r=="file"){t.value="";}else{if(d.Lang.isUndefined(m)){if(o=="input"&&(r=="password"||r=="text")){this.default_value_map[t.name]=t.value;}else{if(o=="input"&&r=="checkbox"){this.default_value_map[t.name]=(t.checked?t.value:"");}else{if(o=="input"&&r=="radio"){var s=this.form[t.name];if(s&&!s.length){this.default_value_map[t.name]=s.value;}else{if(s){this.default_value_map[t.name]=s[0].value;for(var n=0;n<s.length;n++){if(s[n].checked){this.default_value_map[t.name]=s[n].value;break;}}}}}else{if((o=="select"&&r=="select-one")||o=="textarea"){this.default_value_map[t.name]=t.value;}}}}}else{if(o=="input"&&(r=="password"||r=="text")){t.value=m;}else{if(o=="input"&&(r=="checkbox"||r=="radio")){t.checked=(t.value==m);}else{if(o=="select"&&r=="select-one"){t.value=m;if(t.selectedIndex>=0&&t.options[t.selectedIndex].value!==m.toString()){t.selectedIndex=-1;}}else{if(o=="textarea"){t.value=m;}}}}}}}}j.clearMessage=function(n){var m=d.one(n).getAncestorByClassName(d.FormManager.row_marker_class);if(m&&m.hasClass(c())){m.all("."+d.FormManager.status_marker_class).set("innerHTML","");m.removeClass(c());m.all("."+d.FormManager.field_marker_class).removeClass(c());}};j.displayMessage=function(s,n,u,o,v){if(d.Lang.isUndefined(v)){v=true;}s=d.one(s);var m=s.getAncestorByClassName(j.row_marker_class);if(m&&j.statusTakesPrecedence(j.getElementStatus(m),u)){var r=m.all("."+j.field_marker_class);if(r){r.removeClass(c());}if(n){m.one("."+j.status_marker_class).set("innerHTML",n);}var q=j.row_status_prefix+u;m.replaceClass(c(),q);r=s.getAncestorByClassName(j.field_marker_class,true);if(r){r.replaceClass(c(),q);}var w=s.getAncestorByTagName("fieldset");if(w&&j.statusTakesPrecedence(j.getElementStatus(w),u)){w.removeClass(c());w.addClass(j.row_status_prefix+u);}if(!o&&v){m.scrollIntoView();try{s.focus();}catch(t){}}return true;}return false;};d.extend(j,d.Plugin.Host,{getForm:function(){if(!this.form){this.form=d.config.doc.forms[this.form_name];}return this.form;},hasFileInputs:function(){return this.has_file_inputs;},setStatusNode:function(m){this.status_node=d.one(m);},setDefaultValues:function(m){this.default_value_map=m;},setDefaultValue:function(n,m){this.default_value_map[n]=m;},saveCurrentValuesAsDefault:function(){this.default_value_map={};this.button_list=[];i.call(this);},setFunction:function(n,m){this.validation.fn[f(n)]=m;},setRegex:function(o,n,m){o=f(o);if(d.Lang.isString(n)){this.validation.regex[o]=new RegExp(n,m);}else{this.validation.regex[o]=n;}if(!this.validation_msgs[o]||!this.validation_msgs[o].regex){d.error(d.substitute("No error message provided for regex validation of {id}!",{id:o}),null,"FormManager");}},setErrorMessages:function(n,m){this.validation_msgs[f(n)]=m;},addErrorMessage:function(o,m,n){o=f(o);if(!this.validation_msgs[o]){this.validation_msgs[o]={};}this.validation_msgs[o][m]=n;},clearForm:function(){this.clearMessages();this.form.reset();this.postPopulateForm();},populateForm:function(){if(!this.default_value_map){this.default_value_map={};}this.clearMessages();i.call(this);this.postPopulateForm();},postPopulateForm:function(){},isChanged:function(){for(var o=0;o<this.form.elements.length;o++){var r=this.form.elements[o];if(!r.name){continue;}var p=(r.type?r.type.toLowerCase():null);var n=r.tagName.toLowerCase();var m=this.default_value_map[r.name];if(m===null||typeof m==="undefined"){m="";}if(n=="input"&&p=="file"){if(r.value){return true;}}else{if(n=="input"&&(p=="password"||p=="text"||p=="file")){if(r.value!=m){return true;}}else{if(n=="input"&&(p=="checkbox"||p=="radio")){var q=(r.value==m);if((q&&!r.checked)||(!q&&r.checked)){return true;}}else{if((n=="select"&&p=="select-one")||n=="textarea"){if(r.value!=m){return true;}}}}}}return false;},prepareForm:function(){this.getForm();if(!this.prePrepareForm.apply(this,arguments)){return false;}this.populateForm();return this.postPrepareForm.apply(this,arguments);},prePrepareForm:function(){return true;},postPrepareForm:function(){return true;},initFocus:function(){for(var o=0;o<this.form.elements.length;o++){var q=this.form.elements[o];if(q.disabled||q.offsetHeight===0){continue;}var m=q.tagName.toLowerCase();var p=(q.type?q.type.toLowerCase():null);if((m=="input"&&(p=="file"||p=="password"||p=="text"))||m=="textarea"){try{q.focus();}catch(n){}q.select();break;}}},validateForm:function(){this.clearMessages();var n=true;var s=this.form.elements;this.has_file_inputs=j.cleanValues(s);for(var o=0;o<s.length;o++){var t=s[o].id;var m=this.validation_msgs[t];
var r=j.validateFromCSSData(s[o],m);if(r.error){this.displayMessage(s[o],r.error,"error");n=false;continue;}if(r.keepGoing){if(this.validation.regex[t]&&!this.validation.regex[t].test(s[o].value)){this.displayMessage(s[o],m?m.regex:null,"error");n=false;continue;}}var q=this.validation.fn[t];var p=this;if(d.Lang.isFunction(q)){}else{if(d.Lang.isString(q)){q=p[q];}else{if(q&&q.scope){p=q.scope;q=(d.Lang.isString(q.fn)?p[q.fn]:q.fn);}else{q=null;}}}if(q&&!q.call(p,this.form,d.one(s[o]))){n=false;continue;}}if(!this.postValidateForm(this.form)){n=false;}if(!n){this.notifyErrors();}return n;},postValidateForm:function(m){return true;},registerButton:function(m){var n={e:d.Lang.isString(m)||m.tagName?d.one(m):m};this.user_button_list.push(n);},isFormEnabled:function(){return this.enabled;},enableForm:function(){this.setFormEnabled(true);},disableForm:function(){this.setFormEnabled(false);},setFormEnabled:function(m){this.enabled=m;var o=!m;for(var n=0;n<this.button_list.length;n++){this.button_list[n].disabled=o;}for(n=0;n<this.user_button_list.length;n++){var p=this.user_button_list[n];p.e.set("disabled",o);}},hasMessages:function(){return this.has_messages;},hasErrors:function(){return this.has_errors;},getRowStatus:function(n){var m=d.one(n).getAncestorByClassName(j.row_marker_class,true);return j.getElementStatus(m);},clearMessages:function(){this.has_messages=false;this.has_errors=false;if(this.status_node){this.status_node.set("innerHTML","");this.status_node.replaceClass(b(),j.status_none_class);}d.Array.each(this.form.elements,function(o){var m=o.tagName.toLowerCase();var n=(o.type?o.type.toLowerCase():null);if(m!="button"&&n!="submit"&&n!="reset"){j.clearMessage(o);}});d.one(this.form).all("fieldset").removeClass(c());},displayMessage:function(o,p,n,m){if(j.displayMessage(o,p,n,this.has_messages,m)){this.has_messages=true;if(n=="error"){this.has_errors=true;}return true;}else{return false;}},notifyErrors:function(){this.displayFormMessage(j.Strings.validation_error,true,false);},displayFormMessage:function(o,n,m){if(d.Lang.isUndefined(m)){m=true;}if(this.status_node){if(!this.status_node.innerHTML){this.status_node.replaceClass(j.status_none_class,(n?j.status_failure_class:j.status_success_class));this.status_node.set("innerHTML",o);}if(m){this.status_node.scrollIntoView();}}else{}}});d.aggregate(j,d.FormManager);d.FormManager=j;},"gallery-2011.08.24-23-44",{requires:["pluginhost-base","gallery-node-optimizations","gallery-formmgr-css-validation"],optional:["gallery-scrollintoview"]});