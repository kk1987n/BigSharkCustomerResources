<?php

namespace Home\Model;

use Think\Model;

class CustomModel extends Model {

    public function getById($id) {
        return $this->where(array('id' => $id))->find();
    }

    public function getFreeCustomCnt() {
        return $this->where(array('uid' => 0, 'is_success' => 0))->count();
    }

    public function getFreeCustomList($limit, $offset) {
        return $this->where(array('uid' => 0, 'is_success' => 0))->order('utime desc')->limit($limit, $offset)->select();
    }

    public function getSuccessCnt($uid) {
        $where['is_success'] = 1;
        $uid ? $where['uid'] = $uid : null;
        return $this->where($where)->count();
    }

    public function getSuccessList($uid, $limit, $offset) {
        $where['is_success'] = 1;
        $uid ? $where['uid'] = $uid : null;
        return $this->where($where)->limit($limit, $offset)->select();
    }

    public function setCustomUid($cid, $uid) {
        return $this->where(array('id' => $cid))->save(array('uid' => $uid));
    }

    public function checkUserCustom($cid, $uid) {
        return $this->where(array('id' => $cid, 'uid' => $uid))->find();
    }

    public function userCustomLst($uid, $status, $limit, $offset) {
        $where = array('uid' => $uid, 'is_success' => 0);
        $status != null ? $where['status'] = $status : null;
        return $this->where($where)->limit($limit, $offset)->order('utime desc')->select();
    }

    public function userCustomCnt($uid, $status) {
        $where = array('uid' => $uid, 'is_success' => 0);
        $status != null ? $where['status'] = $status : null;
        return $this->where($where)->count();
    }

    public function setCustomStatus($cid, $status) {
        if ($status == null) {
            return true;
        }
        return $this->where(array('id' => $cid))->save(array('status' => $status));
    }

    /**
     * 客户详情
     * @param type $cid
     */
    public function customInfo($cid) {
        return $this
                        ->field('ctm.*,us.name as uname,us2.name as adder')
                        ->alias('ctm')
                        ->join(C('DB_PREFIX') . 'user as us on us.id=ctm.uid', 'left')
                        ->join(C('DB_PREFIX') . 'user as us2 on us2.id=ctm.adder', 'left')
                        ->where(array('ctm.id' => $cid))
                        ->find();
    }

    public function addCustom($data) {
        return $this->add($data);
    }

    public function addExcelCustom($data) {
        if ($data) {
            return $this->addAll($data);
        }
        return false;
    }

    public function successSign($cid, $uid) {
        return $this->where(array('id' => $cid))->save(array('is_success' => 1, 'successer' => $uid));
    }

    public function failSign($cid, $uid) {
        return $this->where(array('id' => $cid))->save(array('is_success' => -1, 'successer' => $uid));
    }

    public function setCustomUtime($cid, $time = null) {
        $time ?: $time = date('Y-m-d H:i:s');
        return $this->where(array('id' => $cid))->save(array('utime' => $time));
    }

    /**
     * 获取超时的被跟踪客户
     * @param type $time 秒
     */
    public function getTimeFollowCustom($time) {
        if (intval($time) <= 0) {
            return false;
        }
        return $this->where('uid>0 && is_success=0 && utime<"' . date('Y-m-d H:i:s', time() - intval($time)) . '"')->select();
    }

    /**
     * 签单榜
     */
    public function signRank($time = null) {
        $where = 'cus.successer>0';
        $time == null ?: $where .= ' and cus.utime>="' . date('Y-m', strtotime($time)) . '"';
        return $this
                        ->alias('cus')
                        ->field('count(*) as cnt,us.`name`,cus.successer')
                        ->join(C('DB_PREFIX') . 'user as us on us.id=cus.successer', 'left')
                        ->where($where)
                        ->group('cus.successer')
                        ->order('cnt desc')
                        ->limit(0, 10)
                        ->select();
    }

    /**
     * 本月大鲨鱼
     * 本月签单数量第一名
     */
    public function bigShark() {
        return $this
                        ->alias('cus')
                        ->field('count(*) as cnt,us.`name`,cus.successer')
                        ->join(C('DB_PREFIX') . 'user as us on us.id=cus.successer', 'left')
                        ->where('cus.successer>0 and cus.utime>="' . date('Y-m') . '"')
                        ->group('cus.successer')
                        ->order('cnt desc')
                        ->find();
    }

}
