/*=========================================================================================
 *@ListModules: Listado de todos los Modulos asociados al portal
 **//*===================================================================================*/
yOSON.AppSchema.modules = {
    'admin': {
        controllers:{
            'index':{
                actions : {
                    'index' : function(){
                        yOSON.AppCore.runModule('validation',{form:"#frm-login"});
                    },
                    'byDefault':function(){}
                },
                allActions:function(){}
            },
            'product':{
                actions : {
                    'index' : function(){
                        yOSON.AppCore.runModule('dataTable',{"url":"/admin/product/list","table":"article"});
                        yOSON.AppCore.runModule('actionDel');
                    },
                    'new':function(){
                        yOSON.AppCore.runModule('validation',{form:"#frm-article"});
                        yOSON.AppCore.runModule('popup');
                    },
                    'edit':function(){
                        yOSON.AppCore.runModule('popup');
                    },
                    'byDefault':function(){}
                },
                allActions:function(){}
            },
            'promotion':{
                actions : {
                    'index' : function(){
                        yOSON.AppCore.runModule('dataTable',{"url":"/admin/promotion/list","table":"article"});
                        yOSON.AppCore.runModule('actionDel');
                    },
                    'new':function(){
                        yOSON.AppCore.runModule('validation',{form:"#frm-article"});
                    },
                    'edit':function(){
                        yOSON.AppCore.runModule('popup');
                    },
                    'byDefault':function(){}
                },
                allActions:function(){}
            },
            'video':{
                actions : {
                    'index' : function(){
                        yOSON.AppCore.runModule('dataTable',{"url":"/admin/video/list","table":"video"});
                        yOSON.AppCore.runModule('actionDel');
                    },
                    'new':function(){
                        yOSON.AppCore.runModule('validation',{form:"#frm-video"});
                    },                  
                    'byDefault':function(){}
                },
                allActions:function(){}
            },
            byDefault : function(){},
            allActions: function(){}
        },
        byDefault : function(){},
        allControllers : function(){}
    },
    byDefault : function(){},
    allModules : function(oMCA){}
};