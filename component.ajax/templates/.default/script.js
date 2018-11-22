function AjaxTest (param) {
    var self = this;
    self.button = $("#bx_test_button");
    self.param = param;
    self.ajaxResult = function(request){
        self.button.after(request);
    };
    self.ajaxError = function (xhr,code,exeption) {
        Pr(xhr);
    };
    self.clickTestButton = function(){
        var data = {};
        data[self.param.postName] = {'AJAX': 'Y'};
        $.ajax(self.param.url,{
            data: data,
            dataType: 'html',
            method: 'POST',
            success: self.ajaxResult,
            error: self.ajaxError
        })
    };
    self.init = function () {
        self.button.on('click', self.clickTestButton);
    };
    $(document).ready(self.init);
}
