(function(doc){
    // 计算屏幕scale
    var _width = 320;
    var _scale = doc.documentElement.clientWidth / _width;
    doc.querySelectorAll('meta[name="viewport"]')[0].setAttribute('content', 'width=320, initial-scale=' + _scale + ', minimum-scale=' + _scale + ', maximum-scale=' + _scale + ', user-scalable=no');
})(document)
//统一绑定 tap 事件
$('*[ontap]').each(function(){
    $(this).hammer({}).on('tap', function(){
        eval($(this).attr('ontap'));
    })
})
//消除移动端点击延迟
$(function(){
    FastClick.attach(document.body);
});
//取消缓存
$.ajaxSettings.cache = false;
//加载入口模块
seajs.use('index.js');
