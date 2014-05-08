yOSON.datable={
    "article":{
        "fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
            $(nRow).addClass("gradeA");
            if(aData[1]!=""){
                $('td:eq(1)',nRow).html( '<img src="'+yOSON.eleHost+'product/imageIcon/origin/'+aData[1]+'" width="25" height="25">' );
            }
            if(aData[2]!=""){
                $('td:eq(2)',nRow).html( '<img src="'+yOSON.eleHost+'product/imageMedium/origin/'+aData[2]+'" width="25" height="25">' );
            }
            if(aData[3]!=""){
                $('td:eq(3)',nRow).html( '<img src="'+yOSON.eleHost+'product/imageLarge/origin/'+aData[3]+'" width="25" height="25">' );
            }
        },
        "aoColumns": [
            null, { "sClass": "center" }, { "sClass": "center" }, { "sClass": "center" }, { "sClass": "center" }
        ]
    },
    "video":{
        "aoColumns": [
            null, { "sClass": "center" }, { "sClass": "center" }
        ]
    }
};