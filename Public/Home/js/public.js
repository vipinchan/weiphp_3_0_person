/**
 * 公共工具类
 * @authors VipinChan (vipinchan@hotmail.com)
 * @date    2018-05-09 08:24:06
 * @version $Id$
 */

/**
 * 异步请求提交表单
 * 提交后返回格式json json格式 {'result':'success|fail',data:{....}}
 *
 * @DateTime 2018-08-19T14:00:18+0800
 * @author vipinchan
 *
 * @version [version]
 * @param {[type]} 
 * @param {Function} 
 * @return {[type]}
 */
function doAjaxSubmit(form, callback) {
    $.Dialog.loading();
    $.ajax({
        data: form.serializeArray(),
        type: 'post',
        dataType: 'json',
        url: form.attr('action'),
        success: function(data) {
            $.Dialog.close();
            callback(data);
        }
    });
}

/**
 * 删除指定参数值
 *
 * @DateTime 2018-07-02T15:54:18+0800
 * @author vipinchan
 *
 * @version [version]
 * @param {[type]} 
 * @param {[type]} 
 * @return {[type]}
 */
function delQueStr(url, ref) {
    var str = "";

    if (url.indexOf('?') != -1)
        str = url.substr(url.indexOf('?') + 1);
    else
        return url;
    var arr = "";
    var returnurl = "";
    var setparam = "";
    if (str.indexOf('&') != -1) {
        arr = str.split('&');
        for (var i in arr) {
            if (arr[i].split('=')[0] != ref) {
                returnurl = returnurl + arr[i].split('=')[0] + "=" + arr[i].split('=')[1] + "&";
            }
        }
        return url.substr(0, url.indexOf('?')) + "?" + returnurl.substr(0, returnurl.length - 1);
    } else {
        arr = str.split('=');
        if (arr[0] == ref)
            return url.substr(0, url.indexOf('?'));
        else
            return url;
    }
}

/**
 * 加密函数
 * @param str 待加密字符串
 * @returns {string}
 */
function encryptStr(str) {
    var c = String.fromCharCode(str.charCodeAt(0) + str.length);

    for (var i = 1; i < str.length; i++) {
        c += String.fromCharCode(str.charCodeAt(i) + str.charCodeAt(i - 1));
    }

    return encodeURIComponent(c);
}

/**
 * 解密函数
 * @param str 待解密字符串
 * @returns {string}
 */
function decryptStr(str) {
    str = decodeURIComponent(str);
    var c = String.fromCharCode(str.charCodeAt(0) - str.length);

    for (var i = 1; i < str.length; i++) {
        c += String.fromCharCode(str.charCodeAt(i) - c.charCodeAt(i - 1));
    }
    return c;
}

/**
 * generate guid
 *
 * @DateTime 2018-05-17T08:25:26+0800
 * @author vipinchan
 *
 * @version [version]
 * @return {[type]}
 */
function newGuid() {
    var d = new Date().getTime();
    var uuid = 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
        var r = (d + Math.random() * 16) % 16 | 0;
        d = Math.floor(d / 16);
        return (c == 'x' ? r : (r & 0x7 | 0x8)).toString(16);
    });
    return uuid;
};

/**
 * 获取星期
 *
 * @DateTime 2018-05-16T11:12:33+0800
 * @author vipinchan
 *
 * @version [version]
 * @param {[string]}  2018-05-16
 * @return {[type]}
 */
function weekFormat(e) {
    var date = new Date();
    var weekday = new Array("周日", "周一", "周二", "周三", "周四", "周五", "周六");
    var year = date.getFullYear(),
        month = date.getMonth(),
        day = date.getDate();

    var eYear = e.substring(0, 4),
        eMonth = e.substring(5, 7),
        eDay = e.substring(8, 10);

    //添加今天、明天、后天
    if (eYear == year && eMonth == month + 1 && eDay == day) {
        return '今天';
    } else if (eYear == year && eMonth == month + 1 && (eDay - day) == 1) {
        return '明天';
    } else if (eYear == year && eMonth == month + 1 && (eDay - day) == 2) {
        return '后天';
    } else {
        return weekday[(new Date(e)).getDay()];
    }
}

/**
 * 验证手机号码
 *
 * @DateTime 2018-05-08T14:42:08+0800
 * @author vipinchan
 *
 * @version [version]
 * @param {[type]} 
 * @return {Boolean}
 */
function isMobile(mobile) {
    if (!mobile || ((mobile + '').length > 0 && !/^1[3|4|5|7|8|9][0-9]{9}$/.test(mobile))) {
        return false;
    }
    return true;
}

/**
 * 验证是护照
 *
 * @DateTime 2018-05-08T14:49:52+0800
 * @author vipinchan
 *
 * @version [version]
 * @param {[type]} 
 * @return {Boolean}
 */
function isPassport(str) {
    if (!str) return false;
    var re1 = /^[a-zA-Z]{5,17}$/;
    var re2 = /^[a-zA-Z0-9]{5,17}$/;
    return re2.test(str) || re1.test(str);
}

/**
 * 验证是中文
 *
 * @DateTime 2018-05-08T14:49:13+0800
 * @author vipinchan
 *
 * @version [version]
 * @param {[type]} 
 * @return {Boolean}
 */
function isChinese(str) {
    var ret = true;
    for (var i = 0; i < str.length; i++)
        ret = ret && (str.charCodeAt(i) >= 10000);
    return ret;
}

/**
 * 验证英文(大小写),数字或者空格
 *
 * @DateTime 2018-05-08T14:48:25+0800
 * @author vipinchan
 *
 * @version [version]
 * @param {[type]} 
 * @return {Boolean}
 */
function isEnglish(str) {
    if (!str || ((str + '').length > 0 && !/^[A-Za-z0-9 ]+$/.test(str))) {
        return false;
    }
    return true;
}

/**
 * 身份证号码验证
 *
 * @DateTime 2018-05-08T14:46:13+0800
 * @author vipinchan
 *
 * @version [version]
 * @param {[type]} 
 * @return info {Object} 身份证信息
 */
function getIdCardInfo(cardNo) {
    var info = {
        isTrue: false, // 身份证号是否有效。默认为 false
        year: null, // 出生年。默认为null
        month: null, // 出生月。默认为null
        day: null, // 出生日。默认为null
        isMale: false, // 是否为男性。默认false
        isChild: false, // 是否为儿童。小于等于12周岁的认为是儿童
        isFemale: false // 是否为女性。默认false
    };
    var tips = '请输入正确的身份证号';
    var today = new Date();

    if (!cardNo || (15 != cardNo.length && 18 != cardNo.length)) {
        info.isTrue = false;
        return info;
    }

    if (15 == cardNo.length) {
        var year = cardNo.substring(6, 8);
        var month = cardNo.substring(8, 10);
        var day = cardNo.substring(10, 12);
        var p = cardNo.substring(14, 15); // 性别位
        var birthday = new Date(year, parseFloat(month) - 1, parseFloat(day));

        var dfYear = Math.floor((today.getFullYear() - year) / 4); // 考虑闰年，4年多一天
        var dt = today.getTime() - birthday.getTime();
        var age = Math.ceil((dt / (24 * 3600 * 1000) - dfYear) / 365); // 向上取整
        // var age = dt/(24*3600*1000*365);
        info.isChild = age > 12 ? false : true;

        // 对于老身份证中的年龄则不需考虑千年虫问题而使用getYear()方法
        if (birthday.getYear() != parseFloat(year) ||
            birthday.getMonth() != parseFloat(month) - 1 ||
            birthday.getDate() != parseFloat(day)) {
            info.isTrue = false;
        } else {
            info.isTrue = true;
            info.year = birthday.getFullYear();
            info.month = birthday.getMonth() + 1;
            info.day = birthday.getDate();
            if (p % 2 == 0) {
                info.isFemale = true;
                info.isMale = false;
            } else {
                info.isFemale = false;
                info.isMale = true;
            }
        }
        return info;
    }

    if (18 == cardNo.length) {
        var year = cardNo.substring(6, 10);
        var month = cardNo.substring(10, 12);
        var day = cardNo.substring(12, 14);
        var p = cardNo.substring(14, 17);
        var birthday = new Date(year, parseFloat(month) - 1, parseFloat(day));

        var dfYear = Math.floor((today.getFullYear() - year) / 4); // 考虑闰年，4年多一天
        var dt = today.getTime() - birthday.getTime();
        var age = Math.ceil((dt / (24 * 3600 * 1000) - dfYear) / 365); // 向上取整
        // var age = dt/(24*3600*1000*365);
        info.isChild = age > 12 ? false : true;

        // 这里用getFullYear()获取年份，避免千年虫问题
        if (birthday.getFullYear() != parseFloat(year) ||
            birthday.getMonth() != parseFloat(month) - 1 ||
            birthday.getDate() != parseFloat(day)) {
            info.isTrue = false;
            return info;
        }

        var Wi = [7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2, 1]; // 加权因子
        var Y = [1, 0, 10, 9, 8, 7, 6, 5, 4, 3, 2]; // 身份证验证位值.10代表X

        // 验证校验位
        var sum = 0; // 声明加权求和变量
        var _cardNo = cardNo.split("");

        if (_cardNo[17].toLowerCase() == 'x') {
            _cardNo[17] = 10; // 将最后位为x的验证码替换为10方便后续操作
        }
        for (var i = 0; i < 17; i++) {
            sum += Wi[i] * _cardNo[i]; // 加权求和
        }
        var i = sum % 11; // 得到验证码所位置

        if (_cardNo[17] != Y[i]) {
            return info.isTrue = false;
        }

        info.isTrue = true;
        info.year = birthday.getFullYear();
        info.month = birthday.getMonth() + 1;
        info.day = birthday.getDate();

        if (p % 2 == 0) {
            info.isFemale = true;
            info.isMale = false;
        } else {
            info.isFemale = false;
            info.isMale = true;
        }
        return info;
    }
    return info;
}

/* eslint linebreak-style: [0] */
function trim(str, isGlobal) {
    let result = str.replace(/(^\s+)|(\s+$)/g, '')
    if (isGlobal) {
        result = result.replace(/\s/g, '')
    }
    return result
}

/**
 * param 将要转为URL参数字符串的对象
 * key URL参数字符串的前缀
 * encode true/false 是否进行URL编码,默认为true
 *
 * return URL参数字符串
 */
function urlEncode(param, key, encode) {
    if (param == null) {
        return ''
    }
    let paramStr = ''
    let t = typeof(param)
    if (t == 'string' || t == 'number' || t == 'boolean') {
        paramStr += '&' + key + '=' + ((encode == null || encode) ? encodeURIComponent(param) : param)
    } else {
        for (let i in param) {
            let k = key == null ? i : key + (param instanceof Array ? '[' + i + ']' : '.' + i)
            paramStr += urlEncode(param[i], k, encode)
        }
    }
    return paramStr
}

/**
 * 获取QueryString的数组
 * @returns {Array|{index: number, input: string}}
 */
function getQueryString() {
    let result = weex.config.bundleUrl.match(new RegExp('[\?\&][^\?\&]+=[^\?\&]+', 'g'))
    for (let i = 0; i < result.length; i++) {
        result[i] = result[i].substring(1)
    }
    return result
}

/**
 * 根据QueryString参数名称获取值
 * @param name
 * @returns {string}
 */
function getQueryStringByName(name) {
    name = name.replace(/[\[\]]/g, '\\$&')
    let regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)')
    let results = regex.exec(weex.config.bundleUrl)
    if (!results || !results[2]) {
        console.log('empty')
        return ''
    }
    console.log(name, decodeURIComponent(results[2].replace(/\+/g, ' ')))
    return decodeURIComponent(results[2].replace(/\+/g, ' '))
}

/**
 * 根据QueryString参数索引获取值
 * @param index
 * @returns {*}
 */
function getQueryStringByIndex(index) {
    if (!index) {
        return ''
    }
    let queryStringList = getQueryString()
    if (index >= queryStringList.length) {
        return ''
    }
    let result = queryStringList[index]
    let startIndex = result.indexOf('=') + 1
    return result.substring(startIndex)
}