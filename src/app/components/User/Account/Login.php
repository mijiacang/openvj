<?php

namespace VJ\User\Account;

use \VJ\I;
use \VJ\Utils;
use \VJ\Models;

class Login
{

    const LOGIN_FROM_AUTH   = 0;
    const LOGIN_FROM_COOKIE = 1;
    const LOGIN_FROM_CERT   = 2;
    const LOGIN_FROM_API    = 3;
    const LOGIN_FROM_SSH    = 4;
    const LOGIN_FROM_MC     = 5;

    /**
     * 使用COOKIE登录
     *
     * @param $token
     * @param $uid
     * @param $key
     *
     * @return array
     */
    public static function fromCookie($token, $uid, $key)
    {

        $token = \VJ\Validator::mongoId($token);
        if ($token == null) {
            return I::error('ARGUMENT_INVALID', 'token');
        }

        $uid = (int)$uid;
        $key = (string)$key;

        $sess = Models\SavedSession::findById($token);

        if (!$sess) {
            return I::error('FAILED');
        }

        // Valid?
        if ($sess->uid !== $uid || $sess->key !== $key) {
            return I::error('FAILED');
        }

        // Session expired?
        if (time() > $sess->exptime->sec) {
            $sess->delete();

            return I::error('FAILED');
        }

        $u = Models\User::findFirst([
            'conditions' => ['uid' => $uid]
        ]);

        // User is deleted
        if ($u == false) {
            return I::error('FAILED');
        }

        // User is banned or marked deleted
        if ($u->banned !== null || $u->deleted !== null) {
            return I::error('FAILED');
        }

        // Login succeeded
        self::_log($uid, self::LOGIN_FROM_COOKIE, true);

        return $u;

    }

    /**
     * 尝试使用用户名密码登录
     *
     * @param     $user
     * @param     $pass_md5
     * @param int $from
     *
     * @return array
     */
    public static function fromPassword($user, $pass, $from = self::LOGIN_FROM_AUTH, $md5 = false)
    {

        $user = strtolower($user);
        $pass = (string)$pass;

        if (strlen($user) === 0) {
            return I::error('ARGUMENT_REQUIRED', 'username');
        }

        if (strlen($pass) === 0) {
            return I::error('ARGUMENT_REQUIRED', 'password');
        }

        $u = Models\User::findFirst([
            'conditions' => ['luser' => $user]
        ]);

        if (!$u) {
            return I::error('NOT_FOUND', 'user');
        }

        if ($u->deleted) {
            return I::error('NOT_FOUND', 'user');
        }

        if ($u->banned) {
            return I::error('USER_BANNED');
        }

        if (!isset($u->passfmt)) {
            $u->passfmt = 0;
        }

        switch ($u->passfmt) {
            case 0:
                // An older hashing method
                $hash = \VJ\User\Account::makeHash_deprecated($user, $pass, $u->salt, $md5);
                break;

            case 1:
                // This account uses a newer password hashing method
                $hash = \VJ\User\Account::makeHash($pass, $u->salt, $md5);
                break;
        }

        if ($u->pass !== $hash) {
            $login_OK = false;
        } else {
            $login_OK = true;
        }

        self::_log($u->uid, $from, $login_OK);

        if (!$login_OK) {
            return I::error('PASSWORD_WRONG');
        }

        // Upgrade old passwords
        if ($u->passfmt == 0 && $md5 == false) {

            $mongo = \Phalcon\DI::getDefault()->getShared('mongo');
            $mongo->User->update(['uid' => $u->uid], [
                '$set' => [
                    'salt'    => \VJ\Security\Randomizer::toHex(30),
                    'pass'    => \VJ\User\Account::makeHash($pass, $u->salt),
                    'passfmt' => 1
                ]
            ]);

        }

        return $u;

    }

    /**
     * 增加登录记录
     *
     * @param $uid
     * @param $from
     * @param $ok
     *
     * @return bool
     */
    private static function _log($uid, $from, $ok)
    {

        $uid  = (int)$uid;
        $from = (int)$from;
        $ok   = (bool)$ok;

        if (isset($_SERVER['HTTP_USER_AGENT'])) {
            $ua = Utils::strcut($_SERVER['HTTP_USER_AGENT']);
        } else {
            $ua = '';
        }

        $log       = new Models\LoginInfo();
        $log->time = new \MongoDate();
        $log->uid  = $uid;
        $log->ok   = $ok;
        $log->from = $from;
        $log->ip   = $_SERVER['REMOTE_ADDR'];
        $log->ua   = $ua;

        return $log->save();

    }

    /**
     * 登录为用户
     *
     * @param $data
     *
     * @return array|bool
     */
    public static function user(Models\User $u)
    {

        global $__SESSION;

        $acl     = \Phalcon\DI::getDefault()->getShared('acl');
        $acldata = $acl->merge(unserialize($u->acl), $u->group);

        // 检查该账号是否可登录
        if (!isset($acldata[PRIV_LOG_IN]) || $acldata[PRIV_LOG_IN] !== true) {
            return I::error('NO_PRIV', 'PRIV_LOG_IN');
        }

        // 检查是否有登录IP限制
        if ($u->ipmatch != null && !preg_match($u->ipmatch, $_SERVER['REMOTE_ADDR'])) {
            return I::error('IP_MISMATCH');
        }

        // 修改最后登录时间
        $mongo = \Phalcon\DI::getDefault()->getShared('mongo');
        $mongo->User->update(['uid' => $u->uid], [
            '$set' => [
                'tlogin'  => time(),
                'iplogin' => $_SERVER['REMOTE_ADDR']
            ]
        ]);

        $u->save();

        \VJ\Session\Utils::newSession();
        \VJ\Security\CSRF::initToken();
        \VJ\Security\Session::initCharacter();

        $data = (array)$u;
        \VJ\Validator::filter($data, [

            'uid'      => 'int',
            'nick'     => null,
            'gmd5'     => null,
            'group'    => 'int',
            'rank'     => null,
            'rp'       => null,
            'vjb'      => null,
            'settings' => null

        ]);

        $data['acl'] = $acldata;

        $__SESSION['user'] = $data;

        return true;

    }

    /**
     * 登录为游客
     *
     * @return bool
     */
    public static function guest()
    {

        global $__SESSION;

        $acl = \Phalcon\DI::getDefault()->getShared('acl');

        $__SESSION['user'] = [

            'uid'      => UID_GUEST,
            'nick'     => NICK_GUEST,
            'gmd5'     => '',
            'group'    => GROUP_GUEST,
            'rank'     => 0,
            'rp'       => 0.0,
            'vjb'      => 0.0,
            'acl'      => $acl->getGroupACL(GROUP_GUEST),
            'settings' => []

        ];

        return true;

    }

}