<?php

namespace Home\Model;

use Think\Model;

class UserModel extends Model {

    public function getByUid($uid) {
        return $this->where(array('id' => $uid))->find();
    }

    public function userList() {
        return $this->alias('us')->field('us.id,us.name,us.acc,us.follow_cnt,FROM_UNIXTIME(us.ltime,"%Y-%m-%d %H:%i:%S") as ltime')->select();
    }

    public function userLogin($account, $hashPwd, $pSalt) {
        $user = $this->getUser($account);
        if ($user) {
            $pwd = $this->cPwd($account, $hashPwd, $user['salt'], $pSalt);
//            print_r($pwd);exit;
            if ($pwd == $user['pwd']) {
                return array('status' => 1, 'msg' => '验证成功', 'user' => $user);
            } else {
                return array('status' => -1, 'msg' => '密码错误');
            }
        } else {
            return array('status' => -1, 'msg' => '账号不存在');
        }
    }

    public function getUser($account) {
        return $this->where(array('acc' => $account))->find();
    }

    public function cSalt($length = 8) {
        return getRandChar($length);
    }

    public function cPwd($account, $hashPwd, $salt, $pSalt) {
        return md5($account . $pSalt . md5($hashPwd) . $salt . md5($account . $hashPwd));
    }

    public function updateLoginTimeIp($uid, $time, $ip) {
        return $this->where(['id' => $uid])->save(array('ltime' => $time, 'lip' => $ip));
    }

    public function addUser($data) {
        return $this->add($data);
    }

    public function resetPwd($uid, $salt, $pwd) {
        return $this->where(array('id' => $uid))->save(array('salt' => $salt, 'pwd' => $pwd));
    }

    public function followCnt($uid, $cnt) {
        return $this->where(array('id' => $uid))->save(array('follow_cnt' => $cnt));
    }

}
