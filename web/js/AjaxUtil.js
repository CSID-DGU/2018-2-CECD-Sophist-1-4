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

function callJsonIgnoreError(url, sendData, callback){
    console.log("Ajax Called : " + url + " with Data(" + sendData + ")");
    $.ajax({
        url : url,
        cache : false,
        async : true,
        method : "post",
        dataType : "text",
        data : sendData,
        success : function(data){
            try{
                console.log("[AJAX RESPONSE] " + data);
                callback(JSON.parse(data));
            }catch (err){
                callback({});
            }
        },
        error : function(req, stat, err){
            console.log("[AJAX ERROR] REQUEST : " + req + " / STATUS : " + stat + " / ERROR : " + err);
        }
    });
}

function callHtml(url, sendData, callback){
    console.log("Ajax Called : " + url + " with Data(" + sendData + ")");
    $.ajax({
        url : url,
        cache : false,
        async : true,
        method : "post",
        dataType : "html",
        data : sendData,
        success : function(data){
            console.log("[AJAX RESPONSE] " + data);
            callback(data);
        },
        error : function(req, stat, err){
            console.log("[AJAX ERROR] REQUEST : " + req + " / STATUS : " + stat + " / ERROR : " + err);
        }
    });
}

function loadPageInto(url, sendData, selector, appendable, onEmpty){
    callHtml(url, sendData, function(data){
        if(data.trim() == ""){
            onEmpty();
        }else{
            if(appendable){
                $(selector).append(data);
            }else{
                $(selector).html(data);
            }
        }
    });
}

function buttonLink(selector, url){
    $(selector).click(function(){
        location.href=url;
    });
}