//post 请求
function _post(url,data = []) {
    var returnData = false;
    $.ajax({
        async: false,
        cache: false,
        type: 'POST',
        url: url,
        data: data,
        dataType: 'json',
        error: function(data) {
            console.log(data);
            swal({
                title: data.responseJSON.msg,
                text: "2秒后自动关闭。",
                type: "error",
                timer: 2000,
                showConfirmButton: false
            });
        },
        success: function(data) {
            swal({
                title: data.msg,
                text: "2秒后自动关闭。",
                type: "success",
                timer: 2000,
                showConfirmButton: false
            });
            // sweetAlert(data.msg, '',"success");
            returnData = data;
        }
    });
    return returnData
}
// get请求
function _get(url,data = []) {
    var returnData = false;
    $.ajax({
        async: false,
        cache: false,
        type: 'GET',
        url: url,
        data: data,
        dataType: 'json',
        error: function(data) {
            console.log(data);
            swal({
                title: data.responseJSON.msg,
                text: "2秒后自动关闭。",
                type: "success",
                timer: 2000,
                showConfirmButton: false
            });
            // sweetAlert(data.responseJSON.msg, '',"error");
        },
        success: function(data) {
            console.log(data);
            swal({
                title: data.msg,
                text: "2秒后自动关闭。",
                type: "success",
                timer: 2000,
                showConfirmButton: false
            });
            returnData = data;
        }
    });
    return returnData
}
// 跳转请求
function _jump(url,data = '') {
    data == '' ? '':url = url+"?"+param;
    setTimeout(function(){
        window.location.href=url;
    }, 2000);
}

// 后台管理Iframe自适应
function iFrameAuto(iFrameName = '') {
    var ifm = document.getElementById(iFrameName);
    var subWeb = document.frames ? document.frames["iframe"].document : ifm.contentDocument;
    if (ifm != null && subWeb != null) {
        ifm.height = subWeb.body.scrollHeight+ 50 > 768 ? subWeb.body.scrollHeight+ 50 : 768;
    }
}
// 设置后台菜单选中效果
function getAdminMenuActiveByThis(self) {
    $('.menu').removeClass('active');
    console.log(self)
    self.addClass('active');
    self.parent().addClass('active');
    self.parent().parent().parent().children(":first").addClass('active');
}


//全选框
$("#checkbox-all").bind("click", function () {
    console.log(1);
    if(this.checked){
        $('[class="checkbox"]:checkbox').prop("checked",this.checked);
    }else{
        $('[class="checkbox"]:checkbox').prop("checked",this.checked);
    }
})
