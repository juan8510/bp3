<?php

    $configfile = "./config.php";
    require($configfile);
    require_once("functions.php");
    
    function getip()
    {
        //客户端IP 或 NONE
        $ip = false;
        if (!empty($_SERVER["HTTP_CLIENT_IP"])) {
            $ip = $_SERVER["HTTP_CLIENT_IP"];
        }
        //多重代理服务器下的客户端真实IP地址（可能伪造）,如果没有使用代理，此字段为空
        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ips = explode(", ", $_SERVER['HTTP_X_FORWARDED_FOR']);
            if ($ip) {
                array_unshift($ips, $ip);
                $ip = FALSE;
            }
            for ($i = 0; $i < count($ips); $i++) {
                if (!preg_match("/^(10│172.16│192.168)./i", $ips[$i])) {
                    $ip = $ips[$i];
                    break;
                }
            }
        }
        return ($ip ? $ip : $_SERVER['REMOTE_ADDR']);
    }
    $remote_ip = getip();
    // getServerIp
    $server_hostname=$_SERVER['SERVER_NAME'];
    $server_ip=gethostbyname($server_hostname);
    
    if($remote_ip == $server_ip){
        // 获取当前路径
        $page_url = getPageUrl();
        $page_url = str_replace("/open.php","",$page_url);
        $refresh_url = $page_url."/admin/refresh_token.php";
        
        //自动刷新token
        $access_token = get_token_refresh($config,$refresh_url);
        if(!$access_token){
            require($configfile);
            echo $config['identify']['access_token'];
        }
        echo $access_token;
    }



    
?>