function callJson(url, sendData, callback){
    console.log("Ajax Called : " + url + " with Data(" + sendData + ")");
    $.ajax({
        url : url,
        cache : false,
        async : true,
        method : "post",
        dataType : "json",
        data : sendData,
        success : function(data){
            console.log("[AJAX RESPONSE] " + data);
            callback(JSON.parse(data));
        },
        error : function(req, stat, err){
            console.log("[AJAX ERROR] REQUEST : " + req + " / STATUS : " + stat + " / ERROR : " + err);
        }
    });
}