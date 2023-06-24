<?php
/*
* 库房仓管小精灵-仓储动态二维码系统
* 作者：喵千寻
*/

/* 全局前端变量设置 */
echo '<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta content="always" name="referrer">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">'; //全局前端
$layui_css = '<link href="//cdnjs.cloudflare.com/ajax/libs/layui/2.8.6/css/layui.css" rel="stylesheet">'; //Layui前端样式
$font_awesome = '<link href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">'; //Font-Awesome图标库
$jquery = '<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>'; //前端脚本jQuery
$layui_js = '<script src="//cdnjs.cloudflare.com/ajax/libs/layui/2.8.6/layui.js"></script>'; //前端脚本layui

/* 设置密码变量 */
function password($value)
{
    $value = sha1('password_') . md5($value) . md5('_encrypt') . sha1($value);
    return '$1$password$'.sha1(md5($value)).'$';
}

/* 安装检测 */
if(!file_exists('./config.php') and @$_GET['action'] != 'install') { //判断配置文件是否存在
    header("location:./?action=install"); //如果配置文件不存在，则认定为未正确安装，跳转至安装程序
/* 安装程序 */
} elseif(@$_GET['action'] === 'install') { //检测是否为安装程序地址，否则不启动安装
    if(file_exists('config.php')) { //如果配置文件存在
        header("location:../"); //跳转至主目录
    } elseif(@$_GET['step'] == null) { //介绍页面(待制作)
        header("location:./?action=install&step=0");
    } elseif(@$_GET['step'] == 0) { //用户协议
        echo '<title>用户协议 - 安装 - 仓管小精灵</title>'.$layui_css.'
            <div class="layui-container">
                <br><br>
                <center><h1>仓管小精灵安装程序</h1></center>
                <br><br>
                <div class="layui-card layui-panel">
                    <div class="layui-card-header"><i class="layui-icon layui-icon-list"></i>&nbsp;用户协议</div>
                    <div class="layui-card-body">';
        foreach(file("compress.zlib://".'LICENSE_CN') as $LICENSE){
            echo $LICENSE.'<br>';
        }
        echo '<br>';
        foreach(file("compress.zlib://".'LICENSE') as $LICENSE){
            echo $LICENSE.'<br>';
        }
        echo '<br>
            <center>
                <button type="button" class="layui-btn layui-btn-disabled"><i class="layui-icon layui-icon-close"></i>&nbsp;不同意</button>
                &nbsp;&nbsp;&nbsp;&nbsp;
                <a href="./?action=install&step=1"><button type="button" class="layui-btn"><i class="layui-icon layui-icon-ok"></i>&nbsp;我同意</button></a>
            </center>
            </div></div><br><br></div>';
    } elseif(@$_GET['step'] == 1) { //环境检测
        if(curl_version()["version"] != false) { //检测Curl是否安装
            $curl_enable = '启用';
        } else {
            $curl_enable = '关闭';
        }
        if(opcache_get_status()["opcache_enabled"] == 1) { //检测OPcache是否安装
            $OPcache_enable = '启用';
        } else {
            $OPcache_enable = '关闭';
        }
        echo '<title>环境检测 - 安装 - 仓管小精灵</title>'.$layui_css.'
            <div class="layui-container">
                <br><br>
                <center><h1>仓管小精灵安装程序</h1></center>
                <br><br>
                <div class="layui-card layui-panel">
                    <br>
                    <center>
                        <div class="layui-progress layui-progress-big" lay-showpercent="true" style="width:95%">
                            <div class="layui-progress-bar" lay-percent="1 / 4"></div>
                        </div>
                    </center>
                    <div class="layui-card-header"><p style="width:25%;text-align:right"><i class="layui-icon layui-icon-set-fill"></i>&nbsp;环境检测</p></div>
                    <div class="layui-card-body">
                        <table class="layui-table">
                            <colgroup>
                                <col width="13%">
                                <col width="29%">
                                <col width="29%">
                                <col width="29%">
                            </colgroup>
                            <thead>
                                <tr>
                                    <th><i class="layui-icon layui-icon-cols"></i>&nbsp;项目</th>
                                    <th><i class="layui-icon layui-icon-screen-restore"></i>&nbsp;最低配置</th>
                                    <th><i class="layui-icon layui-icon-ok"></i>&nbsp;推荐配置</th>
                                    <th><i class="layui-icon layui-icon-util"></i>&nbsp;当前配置</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><i class="layui-icon layui-icon-engine"></i>&nbsp;操作系统</td>
                                    <td>不限制</td>
                                    <td>类Unix</td>
                                    <td><i class="layui-icon layui-icon-ok"></i>&nbsp;'.php_uname('s').'</td>
                                </tr>
                                <tr>
                                    <td><i class="layui-icon layui-icon-circle-dot"></i>&nbsp;PHP版本</td>
                                    <td>5.6</td>
                                    <td>7.0+</td>
                                    <td><i class="layui-icon layui-icon-ok"></i>&nbsp;'.PHP_VERSION.'</td>
                                </tr>
                                <tr>
                                    <td><i class="layui-icon layui-icon-upload-circle"></i>&nbsp;附件上传</td>
                                    <td>不限制</td>
                                    <td>2M+</td>
                                    <td><i class="layui-icon layui-icon-ok"></i>&nbsp;'.get_cfg_var("upload_max_filesize").'</td>
                                </tr>
                                <tr>
                                    <td><i class="layui-icon layui-icon-menu-fill"></i>&nbsp;GD库</td>
                                    <td>1.0</td>
                                    <td>2.0+</td>
                                    <td><i class="layui-icon layui-icon-ok"></i>&nbsp;'.gd_info()["GD Version"].'</td>
                                </tr>
                                <tr>
                                    <td><i class="layui-icon layui-icon-menu-fill"></i>&nbsp;cURL库</td>
                                    <td>不限制</td>
                                    <td>启用</td>
                                    <td><i class="layui-icon layui-icon-ok"></i>&nbsp;'.$curl_enable.'&nbsp;'.curl_version()["version"].'</td>
                                </tr>
                                <tr>
                                    <td><i class="layui-icon layui-icon-find-fill"></i>&nbsp;OPcache</td>
                                    <td>不限制</td>
                                    <td>启用</td>
                                    <td><i class="layui-icon layui-icon-ok"></i>&nbsp;'.$OPcache_enable.'</td>
                                </tr>
                            </tbody>
                        </table>
                        <br>
                        <center>
                            <a href="./?action=install&step=0"><button type="button" class="layui-btn layui-btn-primary"><i class="layui-icon layui-icon-prev"></i>&nbsp;上一步</button></a>
                            &nbsp;&nbsp;&nbsp;&nbsp;
                            <a href="./?action=install&step=2"><button type="button" class="layui-btn">下一步&nbsp;<i class="layui-icon layui-icon-next"></i></button></a>
                        </center>
                    </div>
                </div>
                <br><br>
            </div>'.$layui_js;
    } elseif(@$_GET['step'] == 2) { //数据存储引擎
        echo '<title>数据存储引擎 - 安装 - 仓管小精灵</title>'.$layui_css.'
            <div class="layui-container">
                <br><br>
                <center><h1>仓管小精灵安装程序</h1></center>
                <br><br>
                <div class="layui-card layui-panel">
                    <br>
                    <center>
                        <div class="layui-progress layui-progress-big" lay-showpercent="true" style="width:95%">
                            <div class="layui-progress-bar" lay-percent="2 / 4"></div>
                        </div>
                    </center>
                    <div class="layui-card-header"><p style="width:50%;text-align:right"><i class="layui-icon layui-icon-engine"></i>&nbsp;数据存储引擎</p></div>
                    <div class="layui-card-body">
                        <form class="layui-form" action="./?action=install&step=3" method="post">
                            <div class="layui-form layui-row layui-col-space16">
                                <div class="layui-col-md6" style="width:100%">
                                    <select name="driver">
                                        <option value="json">Json文件</option>
                                    </select>
                                </div>
                            </div>
                            <div class="layui-form"><input type="text" name="json_file_path" value="./database.json" placeholder="Json文件位置" class="layui-input"></div>
                            <br>
                            <center>
                                <a href="./?action=install&step=1"><button type="button" id="submitBtn" class="layui-btn layui-btn-primary"><i class="layui-icon layui-icon-prev"></i>&nbsp;上一步</button></a>
                                &nbsp;&nbsp;&nbsp;&nbsp;
                                <button type="submit" class="layui-btn" lay-submit>下一步&nbsp;<i class="layui-icon layui-icon-next"></i></button>
                            </center>
                        </form>
                    </div>
                </div>
                <br><br>
            </div>'.$layui_js."
                <script>
                    layui.use(['form'], function() {
                        var form = layui.form; //获得 form 模块
                        //提交事件
                        form.on('submit', function(event) {
                            event.preventDefault(); //阻止表单默认提交行为
                            var formElement = event.form; //提交表单
                            var formData = new FormData(formElement);
                            var xhr = new XMLHttpRequest(); //定义发送请求
                            var url = './?action=install&step=3'; //定义URL
                            xhr.open('POST', url, true); //POST发送请求
                            //如果成功则跳转
                            xhr.onreadystatechange = function() {
                                if (xhr.readyState === 4) {
                                    if (xhr.status === 200) {
                                        window.location.href = './?action=install&step=3';
                                    } else {
                                        console.error('请求失败：', xhr.status, xhr.statusText);
                                    }
                                }
                            };
                            xhr.send(formData);
                            return false;
                        });
                    });
                </script>";
    } elseif(@$_GET['step'] == 3) { //设置超级管理员(UID=1)
        echo '<title>站点设置 - 安装 - 仓管小精灵</title>'.$layui_css.'
            <div class="layui-container">
                <br><br>
                <center><h1>仓管小精灵安装程序</h1></center>
                <br><br>
                <div class="layui-card layui-panel">
                    <br>
                    <center>
                        <div class="layui-progress layui-progress-big" lay-showpercent="true" style="width:95%">
                            <div class="layui-progress-bar" lay-percent="3 / 4"></div>
                        </div>
                    </center>
                    <div class="layui-card-header"><p style="width:75%;text-align:right"><i class="fa fa-globe"></i>&nbsp;站点设置</p></div>
                    <div class="layui-card-body">
                        <form class="layui-form" action="./?action=install&step=4" method="post">
                            <input type="hidden" name="driver" value="'.$_POST['driver'].'">
                            <input type="hidden" name="json_file_path" value="'.$_POST['json_file_path'].'">
                            &nbsp;<strong><i class="layui-icon layui-icon-website"></i>&nbsp;站点设置</strong>
                            <div class="layui-form-item">
                                <div class="layui-input-wrap">
                                    <div class="layui-input-prefix"><i class="layui-icon layui-icon-website"></i></div>
                                    <input type="text" name="site_name" lay-verify="required" placeholder="站点名称" autocomplete="off" class="layui-input">
                                </div>
                            </div>
                            <br>
                            &nbsp;<strong><i class="layui-icon layui-icon-username"></i>&nbsp;超级管理员设置</strong>
                            <div class="layui-form-item">
                                <div class="layui-input-wrap">
                                    <div class="layui-input-prefix"><i class="layui-icon layui-icon-username"></i></div>
                                    <input type="text" name="username" lay-verify="required" placeholder="用户名" lay-reqtext="请填写用户名" autocomplete="off" class="layui-input" lay-affix="clear">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <div class="layui-input-wrap">
                                    <div class="layui-input-prefix"><i class="layui-icon layui-icon-password"></i></div>
                                    <input type="password" name="password" lay-verify="required" placeholder="密码" autocomplete="off" class="layui-input" id="reg-password" lay-affix="eye">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <div class="layui-input-wrap">
                                    <div class="layui-input-prefix"><i class="layui-icon layui-icon-password"></i></div>
                                    <input type="password" name="confirmPassword" lay-verify="required|confirmPassword" placeholder="确认密码" autocomplete="off" class="layui-input" lay-affix="eye">
                                </div>
                            </div>
                            <br>
                            <center>
                                <a href="./?action=install&step=2"><button type="button" id="submitBtn" class="layui-btn layui-btn-primary"><i class="layui-icon layui-icon-prev"></i>&nbsp;上一步</button></a>
                                &nbsp;&nbsp;&nbsp;&nbsp;
                                <button type="submit" class="layui-btn" lay-submit>下一步&nbsp;<i class="layui-icon layui-icon-next"></i></button>
                            </center>
                        </form>
                    </div>
                </div>
                <br><br>
            </div>'.$jquery.$layui_js."
            <script>
                layui.use(['form', 'layer'], function() {
                    var form = layui.form; //获得 form 模块
                    // 验证两次密码输入一致性
                    form.verify({
                        confirmPassword: function(value, item) {
                            var passwordValue = $('#reg-password').val();
                            if (value !== passwordValue) {
                                return '两次密码输入不一致';
                            }
                        }
                    });
                    //提交事件
                    form.on('submit', function(event) {
                        event.preventDefault(); //阻止表单默认提交行为
                        var formElement = event.form; //提交表单
                        var formData = new FormData(formElement);
                        var xhr = new XMLHttpRequest(); //定义发送请求
                        var url = './?action=install&step=4'; //定义URL
                        xhr.open('POST', url, true); //POST发送请求
                        //如果成功则跳转
                        xhr.onreadystatechange = function() {
                            if (xhr.readyState === 4) {
                                if (xhr.status === 200) {
                                    window.location.href = './?action=install&step=4';
                                } else {
                                    console.error('请求失败：', xhr.status, xhr.statusText);
                                }
                            }
                        };
                        xhr.send(formData);
                        return false;
                    });
                });
            </script>";
    } elseif(@$_GET['step'] == 4) { //完成安装
        $DATA_JSON_PATH = $_POST['json_file_path'];
        if (empty($DATA_JSON_PATH)) {
            $DATA_JSON_PATH = './database.json';
        }
        file_put_contents('./config.php','<?php'."\r\n".'/*'."\r\n".'* 配置文件'."\r\n".'* 库房仓管小精灵-仓储动态二维码系统'."\r\n".'* 作者：喵千寻'."\r\n".'*/'."\r\n".'$DEMO_SITE = false; //演示站模式'."\r\n".'$DATA_DRIVER = '."'".$_POST['driver']."'".'; //数据存储引擎'."\r\n".'$DATA_JSON_PATH = '."'".$DATA_JSON_PATH."'".'; //json文件路径'."\r\n".'?>'); //建立配置文件并写入配置信息
        $JSON_DATA = array(); //数组形式返回数据
        $JSON_DATA['site_name'] = $_POST['site_name']; //将网站名称写入到数据文件
        //写入用户数据
        $JSON_DATA['users'] = array();
        $user = array();
        $user['uid'] = 1;
        $user['username'] = $_POST['username'];
        $user['password'] = password($_POST['password']);
        $JSON_DATA['users'][] = $user;
        $json = json_encode($JSON_DATA, JSON_UNESCAPED_UNICODE);
        file_put_contents($DATA_JSON_PATH, $json); //写入数据(库)文件
        echo '<title>完成安装 - 安装 - 仓管小精灵</title>'.$layui_css.'
            <div class="layui-container">
                <br><br>
                <center><h1>仓管小精灵安装程序</h1></center>
                <br><br>
                <div class="layui-card layui-panel">
                    <br><center><div class="layui-progress layui-progress-big" lay-showpercent="true" style="width:95%"><div class="layui-progress-bar" lay-percent="4 / 4"></div></div></center>
                    <div class="layui-card-header"><p style="width:100%;text-align:right"><i class="layui-icon layui-icon-ok"></i>&nbsp;完成安装</p></div>
                    <div class="layui-card-body">
                        <h2>恭喜您！！！完成安装啦~</h2><br>
                        <p>数据存储引擎：'.$_POST['driver'].'</p>
                        <p>数据文件地址：'.$_POST['json_file_path'].'</p>
                        <p>站点名称：'.$_POST['site_name'].'</p>
                        <p>超级管理员用户名：'.$_POST['username'].'</p>
                        <p>超级管理员密码：您输入的密码</p>
                        <center><a href="./"><button type="button" class="layui-btn">进入网站</button></a></center>
                    </div>
                </div>
                <br><br>
            </div>'.$layui_js;
    }
} else { //如果正常安装
    require './config.php'; //引用配置
    @session_start(); //启用session
    //@session_destroy () //清除session
    
    //获取并解析数据库文件
    $DATABASE_LOAD=json_decode(file_get_contents("compress.zlib://".$DATA_JSON_PATH), true);
    
    //提示信息
    function information($icon,$title,$content,$link)
    {
        global $DATABASE_LOAD;
        global $font_awesome;
        echo '<title>'.$title.' - '.$DATABASE_LOAD['site_name'].'</title>'.$font_awesome.'<meta http-equiv="refresh" content="3;url='.$link.'"><style>.information_panel{position: absolute;left: 50%;top: 50%;width: 440px;margin-left: -240px;margin-top: -200px;background-color: #ffffff;padding: 20px;border-radius: 4px;box-shadow: 5px 5px 20px #444444;}</style><body bgcolor="skyblue"><div class="information_panel"><center>';
        if($icon == 'error') {
            echo '<i class="fa fa-circle-xmark" style="font-size: 100px; color: red;"></i>';
        } elseif($icon == 'warn') {
            echo '<i class="fa fa-triangle-exclamation" style="font-size: 100px; color: #FFCC00;"></i>';
        } elseif($icon == 'info') {
            echo '<i class="fa fa-circle-info" style="font-size: 100px; color: blue;"></i>';
        } elseif($icon == 'success') {
            echo '<i class="fa fa-circle-check" style="font-size: 100px; color: green;"></i>';
        }
        echo '<h1>'.$content.'</h1><br><h3>页面将在3秒内跳转</h3><a href="'.$link.'">如果没有跳转，请点击这里</a></center></div></body>';
    }
    
    //检测是否登录
    if(!isset($_SESSION['uid']) and @$_GET['action'] != 'login' and @$_GET['action'] != 'register' and @$_GET['action'] != 'forget_password' and @$_GET['action'] != 'qrcode' and @$_GET['action'] != 'wechat' and @$_GET['action'] != 'demo_clear'){
        header("location:./?action=login");
    }
    
    //测试站判断
    if($DEMO_SITE === true) {
        $demo = "<script>layer.alert('测试站<br>数据将会在72小时内清除，仅供体验使用', {icon: 0});</script>";
    }

    //遍历用户数组查找匹配的UID
    $loggedd_user = null;
    foreach ($DATABASE_LOAD['users'] as $user) {
        if ($user['uid'] === $_SESSION['uid']) {
            $loggedd_user = $user;
            break;
        }
    }

    $user_backstage_style = '<style>
        @media (max-width: 768px) {
            .layui-side {
                display: none;
            }
            .layui-body {
                left: 0;
            }
        }
    </style>'; //用户后台样式附加

    if($loggedd_user['uid'] = 1) {
        $user_backstage_switch = '
                <li class="layui-nav-item">
                    <a href="javascript:;">菜单</a>
                    <dl class="layui-nav-child">
                        <dd><a href="./?action=overview">用户后台</a></dd>
                        <dd><a href="javascript:;">管理后台</a></dd>
                    </dl>
                </li>';
    } else {
        $user_backstage_switch = '';
    }

    $user_backstage_nav = '
        <div class="layui-header layui-bg-blue">
            <div class="layui-logo layui-bg-green">'.$DATABASE_LOAD['site_name'].'</div>
            <ul class="layui-nav layui-layout-left">
                <li class="layui-nav-item layui-show-xs-inline-block layui-show-sm" lay-header-event="menuLeft"><i class="layui-icon layui-icon-spread-left"></i></li>'.$user_backstage_switch.'
            </ul>
            <ul class="layui-nav layui-layout-right">
                <li class="layui-nav-item layui-show-sm-inline-block">
                    <a href="javascript:;"><i class="fa fa-user" class="layui-nav-img"></i>&nbsp;&nbsp;<!--<img src="https://cravatar.cn/avatar/" class="layui-nav-img">-->'.$loggedd_user['username'].'</a>
                    <dl class="layui-nav-child">
                        <dd><a href="javascript:;">用户中心</a></dd>
                        <dd><a href="javascript:;">用户设置</a></dd>
                        <dd><a href="./?action=sign_out">注销</a></dd>
                    </dl>
                </li>
            </ul>
        </div>
        <div class="layui-side layui-bg-purple">
            <div class="layui-side-scroll">
                <ul class="layui-nav layui-nav-tree layui-bg-purple" lay-filter="test">
                    <li class="layui-nav-item"><a href="./?action=overview"><i class="fa fa-house"></i> 概览</a></li>
                    <li class="layui-nav-item layui-nav-itemed">
                        <a href="javascript:;"><i class="fa fa-box"></i> 货品管理</a>
                        <dl class="layui-nav-child">
                            <dd><a href="./?action=out_of_warehouse">货品出库</a></dd>
                            <dd><a href="./?action=storage">货品入库</a></dd>
                            <dd><a href="./?action=product_inquiry">货品查询</a></dd>
                            <dd><a href="./?action=product_add">货品新增</a></dd>
                            <dd><a href="./?action=product_edit">货品修改</a></dd>
                            <dd><a href="./?action=product_del">货品删除</a></dd>
                        </dl>
                    </li>
                    <li class="layui-nav-item">
                        <a href="javascript:;"><i class="fa fa-boxes-stacked"></i> 库存管理</a>
                        <dl class="layui-nav-child">
                            <dd><a href="javascript:;">库存修正</a></dd>
                            <dd><a href="javascript:;">库存表格</a></dd>
                            <dd><a href="javascript:;">库存盘点</a></dd>
                            <dd><a href="javascript:;">盘点表</a></dd>
                        </dl>
                    </li>
                    <li class="layui-nav-item">
                        <a href="javascript:;"><i class="fa fa-boxes-packing"></i> 流水管理</a>
                        <dl class="layui-nav-child">
                            <dd><a href="javascript:;">流水查询</a></dd>
                            <dd><a href="javascript:;">流水清除</a></dd>
                            <dd><a href="javascript:;">流水修改</a></dd>
                            <dd><a href="javascript:;">流水删除</a></dd>
                        </dl>
                    </li>
                    <li class="layui-nav-item">
                        <a href="javascript:;"><i class="fa fa-qrcode"></i> 二维码管理</a>
                        <dl class="layui-nav-child">
                            <dd><a href="javascript:;">二维码生成</a></dd>
                            <dd><a href="javascript:;">二维码表</a></dd>
                        </dl>
                    </li>
                    <li class="layui-nav-item">
                        <a href="javascript:;"><i class="fa fa-code"></i> 源码小屋</a>
                        <dl class="layui-nav-child">
                            <!-- <dd><a href="javascript:;">其它</a></dd> -->
                        </dl>
                    </li>
                    <li class="layui-nav-item">
                        <a href="javascript:;"><i class="fa fa-link"></i> 友情链接</a>
                        <dl class="layui-nav-child">
                            <dd><a href="https://www.baidu.com" target="_blank">百度一下</a></dd>
                            <dd><a href="https://www.google.com" target="_blank">谷歌搜索</a></dd>
                        </dl>
                    </li>
                </ul>
            </div>
        </div>'; //用户后台导航

    $user_backstage_footer = '<div class="layui-footer">&copy; 2020-'.date("Y").' SANYIMOE Inc.</div>'; //用户后台底部

    $user_backstage_script = "
        <script>
            layui.use(['element', 'layer', 'util', 'laydate'], function(){
                var element = layui.element;
                var layer = layui.layer;
                var util = layui.util;
                var laydate = layui.laydate;
                var $ = layui.$;

                // 头部事件
                util.event('lay-header-event', {
                    menuLeft: function(othis) { // 左侧菜单事件
                        $('.layui-side').toggle();
                        if ($('.layui-side').is(':hidden')) {
                            $('.layui-body').css('left', 0);
                        } else {
                            $('.layui-body').css('left', '200px');
                        }
                    }
                });

                // 日期选择器
                laydate.render({
                    elem: '#date'
                });

            });
        </script>"; //用户后台脚本附加
    
    if(@$_GET['action'] === 'sign_out'){ //注销用户
        unset($_SESSION['uid']);
        information('success','注销成功','注销成功!!!','./?action=login');
    } elseif(@$_GET['action'] === 'login') { //登录用户
        if(@$_GET['get'] == NULL and !isset($_SESSION['uid'])) {
            echo '<title>用户登录 - '.$DATABASE_LOAD['site_name'].'</title>'.$layui_css.'
            <style>
                .login_panel{position: absolute;left: 50%;top: 50%;width: 440px;margin-left: -240px;margin-top: -200px;background-color: #ffffff;padding: 20px;border-radius: 4px;box-shadow: 5px 5px 20px #444444;}
                .login_container{width: 320px; margin: 21px auto 0;}
                .register .layui-icon{position: relative; display: inline-block; margin: 0 2px; top: 2px; font-size: 26px;}
            </style>
            <body bgcolor="skyblue">
                <div class="login_panel">
                    <center><h2>登录</h2><br>'.$DATABASE_LOAD['site_name'].'</center><hr>
                    <form class="layui-form" action="./?action=login&get=submit" method="post">
                        <div class="login_container">
                            <div class="layui-form-item">
                                <div class="layui-input-wrap">
                                    <div class="layui-input-prefix"><i class="layui-icon layui-icon-username"></i></div>
                                    <input type="text" name="username" lay-verify="required" placeholder="用户名" lay-reqtext="请填写用户名" autocomplete="off" class="layui-input" lay-affix="clear">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <div class="layui-input-wrap">
                                    <div class="layui-input-prefix"><i class="layui-icon layui-icon-password"></i></div>
                                    <input type="password" name="password" lay-verify="required" placeholder="密   码" lay-reqtext="请填写密码" autocomplete="off" class="layui-input" lay-affix="eye">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <input type="checkbox" name="remember" lay-skin="primary" title="记住密码">
                                <a href="./?action=forget_password" style="float: right; margin-top: 7px;">忘记密码？</a>
                            </div>
                            <div class="layui-form-item"><button class="layui-btn layui-btn-fluid" lay-submit>登录</button></div>
                            <div class="layui-form-item register"><a href="./?action=register">没有账号？注册帐号</a></div>
                        </div>
                    </form>
                </div>
            </body>'.$jquery.$layui_js."
            <script>
                layui.use(['form'], function() {
                    var form = layui.form; //获得 form 模块
                    //提交事件
                    form.on('submit', function(event) {
                        event.preventDefault(); //阻止表单默认提交行为
                        var formElement = event.form; //提交表单
                        var formData = new FormData(formElement);
                        var xhr = new XMLHttpRequest(); //定义发送请求
                        var url = './?action=install&step=3'; //定义URL
                        xhr.open('POST', url, true); //POST发送请求
                        //如果成功则跳转
                        xhr.onreadystatechange = function() {
                            if (xhr.readyState === 4) {
                                if (xhr.status === 200) {
                                    window.location.href = './?action=install&step=3';
                                } else {
                                    console.error('请求失败：', xhr.status, xhr.statusText);
                                }
                            }
                        };
                        xhr.send(formData);
                        return false;
                    });
                });
            </script>".$demo;
        } elseif(@$_GET['get'] == "submit") {
            //遍历用户数组查找匹配的用户名
            $found_user = null;
            foreach ($DATABASE_LOAD['users'] as $user) {
                if ($user['username'] === $_POST['username']) {
                    $found_user = $user;
                    break;
                }
            }
            
            //如果找到匹配的用户，则检测用户名及密码是否正确
            if ($found_user) {
                $uid = $found_user['uid'];
                //检测密码是否正确
                if (password($_POST['password']) === $found_user['password']) {
                    $_SESSION['uid'] = $found_user['uid'];
                    information('success','登录成功','登录成功!!!','./?action=overview');
                } else {
                    //如果密码不正确
                    information('error','密码错误','密码错误!!!','./?action=login');
                }
                
            } else {
                //如果用户不存在，则显示不存在提示
                information('error','用户不存在','用户不存在!!!','./?action=login');
            }
        } else {
            header("location:../?action=overview");
        }
    } elseif(@$_GET['action'] === 'register') { //用户注册
        echo '<title>用户注册 - '.$DATABASE_LOAD['site_name'].'</title>';
        information('info','敬请期待','功能开发中，敬请期待!!!','./?action=login');
    } elseif(@$_GET['action'] === 'forget_password'){
        echo '<title>忘记密码 - '.$DATABASE_LOAD['site_name'].'</title>';
        information('info','敬请期待','功能开发中，敬请期待!!!','./?action=login');
    } elseif(@$_GET['action'] === 'overview') {
        echo '<title>概览 - '.$DATABASE_LOAD['site_name'].'</title>'.$layui_css.$font_awesome.$user_backstage_style.'
            <body>
                <div class="layui-layout layui-layout-admin">
                    '.$user_backstage_nav.'
                    <div class="layui-body">
                        <div style="padding: 15px;">
                            <h1>欢迎!!! '.$loggedd_user['username'].'</h1><br>
                            <div class="layui-row layui-col-space15">
                                <div class ="layui-col-md8">
                                    <div class ="layui-row layui-col-space15">
                                        <div class="layui-col-md6">
                                            <div class="layui-card layui-panel">
                                                <div class="layui-card-header"><strong>货品管理</strong></div>
                                                <div class="layui-card-body">
                                                    <div style="width: 100%; height: 316px;">
                                                        <ul class="layui-row layui-col-space10 layui-this">
                                                            <li class="layui-col-xs3">
                                                                <a href="./?action=out_of_warehouse">
                                                                    <div class="layui-carousel"><br><center><i class="fa fa-boxes-packing" style="font-size: 50px;"></i></center><br></div>
                                                                    <center><strong>货品出库</strong></center>
                                                                </a>
                                                            </li>
                                                            <li class="layui-col-xs3">
                                                                <a href="./?action=storage">
                                                                    <div class="layui-carousel"><br><center><i class="fa fa-boxes-stacked" style="font-size: 50px;"></i></center><br></div>
                                                                    <center><strong>货品入库</strong></center>
                                                                </a>
                                                            </li>
                                                            <li class="layui-col-xs3">
                                                                <a href="./?action=product_inquiry">
                                                                    <div class="layui-carousel"><br><center><i class="fa fa-search" style="font-size: 50px;"></i></center><br></div>
                                                                    <center><strong>货品查询</strong></center>
                                                                </a>
                                                            </li>
                                                            <li class="layui-col-xs3">
                                                                <a href="./?action=product_add">
                                                                    <div class="layui-carousel"><br><center><i class="fa fa-add" style="font-size: 50px;"></i></center><br></div>
                                                                    <center><strong>货品新增</strong></center>
                                                                </a>
                                                            </li>
                                                            <li class="layui-col-xs3">
                                                                <a href="./?action=product_edit">
                                                                    <div class="layui-carousel"><br><center><i class="fa fa-pen-to-square" style="font-size: 50px;"></i></center><br></div>
                                                                    <center><strong>货品修改</strong></center>
                                                                </a>
                                                            </li>
                                                            <li class="layui-col-xs3">
                                                                <a href="./?action=product_del">
                                                                    <div class="layui-carousel"><br><center><i class="fa fa-trash" style="font-size: 50px;"></i></center><br></div>
                                                                    <center><strong>货品删除</strong></center>
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="layui-col-md6">
                                            <div class="layui-card layui-panel">
                                                <div class="layui-card-header"><strong>库存管理</strong></div>
                                                <div class="layui-card-body">
                                                    <div style="width: 100%; height: 120px;">
                                                        <ul class="layui-row layui-col-space10 layui-this">
                                                            <li class="layui-col-xs3">
                                                                <a href="./?action=out_of_warehouse">
                                                                    <div class="layui-carousel"><br><center><i class="fa fa-wrench" style="font-size: 50px;"></i></center><br></div>
                                                                    <center><strong>库存修正</strong></center>
                                                                </a>
                                                            </li>
                                                            <li class="layui-col-xs3">
                                                                <a href="./?action=storage">
                                                                    <div class="layui-carousel"><br><center><i class="fa fa-bars" style="font-size: 50px;"></i></center><br></div>
                                                                    <center><strong>库存表格</strong></center>
                                                                </a>
                                                            </li>
                                                            <li class="layui-col-xs3">
                                                                <a href="">
                                                                    <div class="layui-carousel"><br><center><i class="fa fa-warehouse" style="font-size: 50px;"></i></center><br></div>
                                                                    <center><strong>库存盘点</strong></center>
                                                                </a>
                                                            </li>
                                                            <li class="layui-col-xs3">
                                                                <a href="">
                                                                    <div class="layui-carousel"><br><center><i class="fa fa-check" style="font-size: 50px;"></i></center><br></div>
                                                                    <center><strong>盘点表</strong></center>
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="layui-col-md6">
                                            <div class="layui-card layui-panel">
                                                <div class="layui-card-header"><strong>流水管理</strong></div>
                                                <div class="layui-card-body">
                                                    <div style="width: 100%; height: 120px;">
                                                        <ul class="layui-row layui-col-space10 layui-this">
                                                            <li class="layui-col-xs3">
                                                                <a href="./?action=out_of_warehouse">
                                                                    <div class="layui-carousel"><br><center><i class="fa fa-search" style="font-size: 50px;"></i></center><br></div>
                                                                    <center><strong>流水查询</strong></center>
                                                                </a>
                                                            </li>
                                                            <li class="layui-col-xs3">
                                                                <a href="./?action=storage">
                                                                    <div class="layui-carousel"><br><center><i class="fa fa-xmark" style="font-size: 50px;"></i></center><br></div>
                                                                    <center><strong>流水清除</strong></center>
                                                                </a>
                                                            </li>
                                                            <li class="layui-col-xs3">
                                                                <a href="">
                                                                    <div class="layui-carousel"><br><center><i class="fa fa-pen-to-square" style="font-size: 50px;"></i></center><br></div>
                                                                    <center><strong>流水修改</strong></center>
                                                                </a>
                                                            </li>
                                                            <li class="layui-col-xs3">
                                                                <a href="">
                                                                    <div class="layui-carousel"><br><center><i class="fa fa-trash" style="font-size: 50px;"></i></center><br></div>
                                                                    <center><strong>流水删除</strong></center>
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="layui-col-md6">
                                            <div class="layui-card layui-panel">
                                                <div class="layui-card-header"><strong>二维码管理</strong></div>
                                                <div class="layui-card-body">
                                                    <div style="width: 100%; height: 120px;">
                                                        <ul class="layui-row layui-col-space10 layui-this">
                                                            <li class="layui-col-xs3">
                                                                <a href="./?action=out_of_warehouse">
                                                                    <div class="layui-carousel"><br><center><i class="fa fa-search" style="font-size: 50px;"></i></center><br></div>
                                                                    <center><strong>1</strong></center>
                                                                </a>
                                                            </li>
                                                            <li class="layui-col-xs3">
                                                                <a href="./?action=storage">
                                                                    <div class="layui-carousel"><br><center><i class="fa fa-xmark" style="font-size: 50px;"></i></center><br></div>
                                                                    <center><strong>2</strong></center>
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="layui-col-md4">
                                    <div class="layui-card layui-panel">
                                        <div class="layui-card-header">用户信息</div>
                                        <div class="layui-card-body">
                                            <table class="layui-table">
                                                <colgroup>
                                                    <col width="110">
                                                    <col>
                                                </colgroup>
                                                <tr>
                                                    <td>用户名</td>
                                                    <td>'.$loggedd_user['username'].'</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="layui-col-md4">
                                    <div class="layui-card layui-panel">
                                        <div class="layui-card-header">系统信息</div>
                                        <div class="layui-card-body">
                                            <table class="layui-table">
                                                <colgroup>
                                                    <col width="110">
                                                    <col>
                                                </colgroup>
                                                <tr>
                                                    <td>站点名称</td>
                                                    <td>'.$DATABASE_LOAD['site_name'].'</td>
                                                </tr>
                                                <tr>
                                                    <td>系统版本</td>
                                                    <td>Warehouse_Manage_Sid</td>
                                                </tr>
                                                <tr>
                                                    <td>内部版本号</td>
                                                    <td>Sid</td>
                                                </tr>
                                                <tr>
                                                    <td>最后更新</td>
                                                    <td>06/24/2023</td>
                                                </tr>
                                                <tr>
                                                    <td>测试站</td>
                                                    <td>'.$DEMO_SITE.'</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="layui-col-md4">
                                    <div class="layui-card layui-panel">
                                        <div class="layui-card-header">支持一下</div>
                                        <div class="layui-card-body">
                                            广告区域
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>'.$user_backstage_footer.'
                </div>
            </body>'.$layui_js.$user_backstage_script.$demo;
    } elseif(@$_GET['action'] === 'out_of_warehouse') {
        echo '<title>货品出库 - '.$DATABASE_LOAD['site_name'].'</title>'.$layui_css.$font_awesome.$user_backstage_style.'
            <body>
                <div class="layui-layout layui-layout-admin">
                    '.$user_backstage_nav.'
                    <div class="layui-body">
                        <div style="padding: 15px;">
                            <div class="layui-card layui-panel">
                                <div class="layui-card-header"><strong>货品出库</strong></div>
                                <div class="layui-card-body">
                                    <form class="layui-form" action="">
                                        <div class="layui-form-item">
                                            <div class="layui-input-group" style="width: 100%">
                                                <div class="layui-input-prefix">
                                                    <i class="fa fa-boxes-packing" style="font-size: 40px;"></i>
                                                </div>
                                                <input type="text" name="search" lay-verify="required" lay-affix="clear" placeholder="请输入要出库的货品牌号、货名或货位" autocomplete="off" class="layui-input">
                                                <div class="layui-input-suffix">
                                                    <button type="submit" class="layui-btn" lay-submit>查询</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div><br><br>
                        </div>
                    </div>'.$user_backstage_footer.'
                </div>
            </body>'.$layui_js.$user_backstage_script;
    } elseif(@$_GET['action'] === 'storage') {
        echo '<title>货品入库 - '.$DATABASE_LOAD['site_name'].'</title>'.$layui_css.$font_awesome.$user_backstage_style.'
            <body>
                <div class="layui-layout layui-layout-admin">
                    '.$user_backstage_nav.'
                    <div class="layui-body">
                        <div style="padding: 15px;">
                            <div class="layui-card layui-panel">
                                <div class="layui-card-header"><strong>货品入库</strong></div>
                                <div class="layui-card-body">
                                    <form class="layui-form" action="">
                                        <div class="layui-form-item">
                                            <div class="layui-input-group" style="width: 100%">
                                                <div class="layui-input-prefix">
                                                    <i class="fa fa-boxes-stacked" style="font-size: 40px;"></i>
                                                </div>
                                                <input type="text" name="search" lay-verify="required" lay-affix="clear" placeholder="请输入要入库的货品牌号、货名或货位" autocomplete="off" class="layui-input">
                                                <div class="layui-input-suffix">
                                                    <button type="submit" class="layui-btn" lay-submit>查询</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div><br><br>
                        </div>
                    </div>'.$user_backstage_footer.'
                </div>
            </body>'.$layui_js.$user_backstage_script;
    } elseif(@$_GET['action'] === 'product_inquiry'){
        echo '<title>货品查询 - '.$DATABASE_LOAD['site_name'].'</title>'.$layui_css.$font_awesome.$user_backstage_style.'
            <body>
                <div class="layui-layout layui-layout-admin">
                    '.$user_backstage_nav.'
                    <div class="layui-body">
                        <div style="padding: 15px;">
                            <div class="layui-card layui-panel">
                                <div class="layui-card-header"><strong>货品查询</strong></div>
                                <div class="layui-card-body">
                                    <form class="layui-form" action="">
                                        <div class="layui-form-item">
                                            <div class="layui-input-group" style="width: 100%">
                                                <div class="layui-input-prefix">
                                                    <i class="fa fa-search" style="font-size: 40px;"></i>
                                                </div>
                                                <input type="text" name="search" lay-verify="required" lay-affix="clear" placeholder="请输入要查询的货品牌号、货名或货位" autocomplete="off" class="layui-input">
                                                <div class="layui-input-suffix">
                                                    <button type="submit" class="layui-btn" lay-submit>查询</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div><br><br>
                        </div>
                    </div>'.$user_backstage_footer.'
                </div>
            </body>'.$layui_js.$user_backstage_script;
    } elseif(@$_GET['action'] === 'product_add') {
        echo '<title>货品新增 - '.$DATABASE_LOAD['site_name'].'</title>'.$layui_css.$font_awesome.$user_backstage_style.'
            <body>
                <div class="layui-layout layui-layout-admin">
                    '.$user_backstage_nav.'
                    <div class="layui-body">
                        <div style="padding: 15px;">
                            <div class="layui-card layui-panel">
                                <div class="layui-card-header"><strong>货品新增</strong></div>
                                <div class="layui-card-body">
                                    <form class="layui-form layui-form-pane" action="">
                                        <div class="layui-form-item">
                                            <label class="layui-form-label">牌&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;号：</label>
                                            <div class="layui-input-block">
                                                <input type="text" name="grade" lay-verify="required" placeholder="请输入要新增的货品牌号" autocomplete="off" class="layui-input">
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label">货&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;名：</label>
                                            <div class="layui-input-block">
                                                <input type="text" name="name" lay-verify="required" placeholder="请输入要新增的货品名称" autocomplete="off" class="layui-input">
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label">货&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;位：</label>
                                            <div class="layui-input-block">
                                                <input type="text" name="space" lay-verify="required" placeholder="请输入要新增的货位信息" autocomplete="off" class="layui-input">
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label">日&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;期：</label>
                                            <div class="layui-input-block layui-input-wrap">
                                                <div class="layui-input-prefix"><i class="layui-icon layui-icon-date"></i></div>
                                                <input type="text" name="date" id="date" lay-verify="date" placeholder="请输入货品新增日期" autocomplete="off" class="layui-input">
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label">初期库存：</label>
                                            <div class="layui-input-block">
                                                <input type="text" name="initial_inventory" lay-verify="required" placeholder="请输入要新增的货品初期库存" autocomplete="off" class="layui-input">
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <div class="layui-input-block">
                                                <button type="submit" class="layui-btn" lay-submit style="width: 92%;"><i class="fa fa-add"></i> 新增</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div><br><br>
                        </div>
                    </div>'.$user_backstage_footer.'
                </div>
            </body>'.$layui_js.$user_backstage_script;
    } elseif(@$_GET['action'] === 'product_edit') {
        echo '<title>货品修改 - '.$DATABASE_LOAD['site_name'].'</title>'.$layui_css.$font_awesome.$user_backstage_style.'
            <body>
                <div class="layui-layout layui-layout-admin">
                    '.$user_backstage_nav.'
                    <div class="layui-body">
                        <div style="padding: 15px;">
                            <div class="layui-card layui-panel">
                                <div class="layui-card-header"><strong>货品修改</strong></div>
                                <div class="layui-card-body">
                                    <form class="layui-form" action="">
                                        <div class="layui-form-item">
                                            <div class="layui-input-group" style="width: 100%">
                                                <div class="layui-input-prefix">
                                                    <i class="fa fa-pen-to-square" style="font-size: 40px;"></i>
                                                </div>
                                                <input type="text" name="search" lay-verify="required" lay-affix="clear" placeholder="请输入要修改的货品牌号、货名或货位" autocomplete="off" class="layui-input">
                                                <div class="layui-input-suffix">
                                                    <button type="submit" class="layui-btn" lay-submit>查询</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div><br><br>
                        </div>
                    </div>'.$user_backstage_footer.'
                </div>
            </body>'.$layui_js.$user_backstage_script;
    } elseif(@$_GET['action'] === 'product_del') {
        echo '<title>货品删除 - '.$DATABASE_LOAD['site_name'].'</title>'.$layui_css.$font_awesome.$user_backstage_style.'
            <body>
                <div class="layui-layout layui-layout-admin">
                    '.$user_backstage_nav.'
                    <div class="layui-body">
                        <div style="padding: 15px;">
                            <div class="layui-card layui-panel">
                                <div class="layui-card-header"><strong>货品删除</strong></div>
                                <div class="layui-card-body">
                                    <form class="layui-form" action="">
                                        <div class="layui-form-item">
                                            <div class="layui-input-group" style="width: 100%">
                                                <div class="layui-input-prefix">
                                                    <i class="fa fa-trash" style="font-size: 40px;"></i>
                                                </div>
                                                <input type="text" name="search" lay-verify="required" lay-affix="clear" placeholder="请输入要删除的货品牌号、货名或货位" autocomplete="off" class="layui-input">
                                                <div class="layui-input-suffix">
                                                    <button type="submit" class="layui-btn" lay-submit>查询</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div><br><br>
                        </div>
                    </div>'.$user_backstage_footer.'
                </div>
            </body>'.$layui_js.$user_backstage_script;
    }
}
?>
