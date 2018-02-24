<?php

namespace Home\Service;

use Home\Model\UserModel;

class User {

    private $userMd;

    public function __construct() {
        $this->userMd = new UserModel();
    }

    public function getByUid($uid) {
        return $this->userMd->getByUid($uid);
    }

    public function getUserList() {
        return $this->userMd->userList();
    }

    public function userLogin($account, $hashPwd) {
        $user = $this->userMd->userLogin($account, $hashPwd, C('PUB_SALT'));
        $time = $this->updateLoginTimeIp($user['user']['id']);
        if ($time) {
            $user['ltime'] = $time;
            return $user;
        }
        return false;
    }

    public function updateLoginTimeIp($uid) {
        $time = time();
        $ip = get_client_ip(1);
        if ($this->userMd->updateLoginTimeIp($uid, $time, $ip)) {
            return $time;
        }
        return false;
    }

    public function getByAcc($acc) {
        return $this->userMd->getUser($acc);
    }

    public function addUser($data) {
        $user = $this->getByAcc($data['acc']);
        if ($user) {
            return false;
        }
        $data['salt'] = $this->userMd->cSalt();
        $data['pwd'] = $this->userMd->cPwd($data['acc'], $data['hashPwd'], $data['salt'], C('PUB_SALT'));
        unset($data['hashPwd']);
        return $this->userMd->addUser($data);
    }

    public function resetPwd($uid, $pass = null) {
        $user = $this->getByUid($uid);
        if (!$user) {
            return false;
        }
        $uPwd = $pass ? $pass : rand(100000, 999999);
        $salt = $this->userMd->cSalt();
        $pwd = $this->userMd->cPwd($user['acc'], md5($uPwd), $salt, C('PUB_SALT'));
        if ($this->userMd->resetPwd($uid, $salt, $pwd)) {
            return $uPwd;
        }
        return false;
    }

    public function followCnt($uid, $cnt) {
        $user = $this->getByUid($uid);
        if (!$user) {
            return false;
        }
        if ($user['follow_cnt'] == $cnt) {
            return true;
        }
        return $this->userMd->followCnt($uid, $cnt > 0 ? $cnt : 0);
    }

}
