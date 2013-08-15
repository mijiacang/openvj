<?php

namespace VJ\Security;

class SSL
{

    /**
     * 强制跳转到SSL（对搜索引擎不跳转）
     */
    public static function enforce()
    {

        global $__SESSION;

        if (isset($_GET['nossl'])) {

            $option = strtolower($_GET['nossl']);

            if ($option === 'false' || $option === 'off') {
                $__SESSION->remove('option-nossl');
            } else {
                $__SESSION->set('option-nossl', true);
            }

        }

        if (!ENV_SSL && !$__SESSION->has('option-nossl')) {

            if
            (
                !isset($_SERVER['HTTP_USER_AGENT'])
                || stripos($_SERVER['HTTP_USER_AGENT'], 'Baiduspider') === false
                && stripos($_SERVER['HTTP_USER_AGENT'], 'Sogou web spider') === false
                && stripos($_SERVER['HTTP_USER_AGENT'], 'Sosospider') === false
            ) {

                header('HTTP/1.1 301 Moved Permanently');
                header('Location: https://'.ENV_HOST.$_SERVER['REQUEST_URI']);
                exit();

            }

        }

    }

}