<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Home\Service;

use Home\Model\CustomModel;
use Home\Model\FollowModel;
use Home\Model\StatusModel;

/**
 * Description of Custom
 *
 * @author Administrator
 */
class Custom {

    private $customMd;
    private $followMd;
    private $followStatusMd;

    public function __construct() {
        $this->customMd = new CustomModel();
        $this->followMd = new FollowModel();
        $this->followStatusMd = new StatusModel();
    }

    /**
     * 公共资源
     * @return type
     */
    public function getFreeCustomCnt() {
        return $this->customMd->getFreeCustomCnt();
    }

    /**
     * 公共客户资源
     * @param type $limit
     * @param type $offset
     * @return type
     */
    public function getFreeCustomList($limit, $offset) {
        return $this->customMd->getFreeCustomList($limit, $offset);
    }

    /**
     * 成功签订
     * @return type
     */
    public function getSuccessCnt($uid) {
        return $this->customMd->getSuccessCnt($uid);
    }

    /**
     * 成功签订
     * @param type $limit
     * @param type $offset
     * @return type
     */
    public function getSuccessList($uid, $limit, $offset) {
        return $this->customMd->getSuccessList($uid, $limit, $offset);
    }

    public function getCustom($cid) {
        return $this->customMd->getById($cid);
    }

    /**
     * 用户开始跟踪
     * @param type $cid
     * @param type $uid
     * @return boolean
     */
    public function startFollowCustom($cid, $uid) {
        if (!$cid || !$uid) {
            return array('status' => -1, 'msg' => '信息错误,刷新重试');
        }
        $userMd = new User();
        $user = $userMd->getByUid($uid);
        if (!$user['follow_cnt']) {//用户可跟踪数量为0
            return array('status' => -1, 'msg' => '可跟踪数量为0');
        }
        $userFollowCnt = $this->userCustomCnt($uid);
        if ($user['follow_cnt'] <= $userFollowCnt) {
            return array('status' => -1, 'msg' => '可跟踪数量为' . $user['follow_cnt']);
        }
        $custom = $this->getCustom($cid);
        if ($custom['uid'] > 0) {
            return array('status' => -1, 'msg' => '当前客户已被跟踪');
        }
        if ($this->setCustomUid($cid, $uid) && $this->addFollow($cid, 0, $uid, '新开始跟踪')) {
            return array('status' => 1, 'msg' => '成功');
        }
        return array('status' => -1, 'msg' => '跟踪失败');
    }

    /**
     * 用户放弃跟踪
     * @param type $cid
     * @param type $uid
     * @return boolean
     */
    public function dropCustomFollow($cid, $uid) {
        if ($this->customMd->checkUserCustom($cid, $uid) && $this->setCustomUid($cid, 0) && $this->addFollow($cid, 0, $uid, '放弃跟踪')) {
            return true;
        }
        return FALSE;
    }

    public function setCustomUid($cid, $uid) {
        return $this->customMd->setCustomUid($cid, $uid);
    }

    /**
     * 
     * @param type $cid
     * @param type $status
     * @param type $uid
     * @param type $content
     * @return boolean
     */
    public function addFollow($cid, $status, $uid, $content) {
        if ($this->followMd->addFollow($cid, $status, $uid, $content)) {
            $this->customMd->setCustomUtime($cid);
            $this->customMd->setCustomStatus($cid, $status);
            return true;
        }
        return false;
    }

    /**
     * 某用户的跟踪客户数量
     * @param type $uid
     * @return type
     */
    public function userCustomCnt($uid, $status) {
        return $this->customMd->userCustomCnt($uid, $status);
    }

    /**
     * 某用户的跟踪客户列表
     * @param type $uid
     * @param type $limit
     * @param type $offset
     * @return type
     */
    public function userCustomLst($uid, $status, $limit, $offset) {
        return $this->customMd->userCustomLst($uid, $status, $limit, $offset);
    }

    public function getFollowStatus() {
        return $this->followStatusMd->dlist();
    }

    /**
     * 客户跟踪时间线
     * @param type $cid
     */
    public function customFollowInfo($cid) {
        return $this->followMd->followInfo($cid);
    }

    /**
     * 客户详情
     * @param type $cid
     */
    public function customInfo($cid) {
        return $this->customMd->customInfo($cid);
    }

    public function addCustom($data, $uid) {
        $row['uid'] = $data['followNow'] == 1 ? intval($uid) : 0;
        $row['adder'] = $uid;
        $row['name'] = $data['name'];
        $row['age'] = $data['age'];
        $row['sex'] = $data['sex'] == 1 || $data['sex'] == 0 ? $data['sex'] : ($data['sex'] == '男' ? 1 : 0);
        $row['phone'] = $data['phone'];
        $row['is_success'] = 0;
        $row['address'] = $data['address'] ? $data['address'] : '未知';
        $row['zuoji'] = $data['zuoji'] ? $data['zuoji'] : '';
        $row['keyword'] = $data['keyword'] ? $data['keyword'] : '';
        $row['des'] = $data['des'] ? $data['des'] : '';
        $row['from'] = $data['from'] ? $data['from'] : '未知';
        $row['wx'] = $data['wx'] ? $data['wx'] : '';
        $row['address'] = $data['address'] ? $data['address'] : '未知';
        $row['qq'] = $data['qq'] ? $data['qq'] : '';
        $row['email'] = $data['email'] ? $data['email'] : '';
        $row['web'] = $data['web'] ? $data['web'] : '';
        $row['company'] = $data['company'] ? $data['company'] : '未知';
        $row['comp_type'] = $data['comp_type'] ? $data['comp_type'] : '未知';
        $row['comp_scale'] = $data['comp_scale'] ? $data['comp_scale'] : '未知';
        $row['comp_skill'] = $data['comp_skill'] ? $data['comp_skill'] : '未知';
        $row['comp_scale'] = $data['comp_scale'] ? $data['comp_scale'] : '未知';
        $row['content'] = $data['content'] ? $data['content'] : '';
        $row['atime'] = date('Y-m-d H:i:s');
        return $this->customMd->addCustom($row);
    }

    public function addExcelCustom($data, $uid) {
        return $this->customMd->addExcelCustom($this->doExcelData($data, $uid));
    }

    public function doExcelData($data, $uid) {
        $rs = array();
        foreach ($data as $da) {
            $row = array();
            $row['uid'] = $da[0];
            $row['adder'] = $uid;
            $row['name'] = $da[1];
            $row['age'] = $da[2];
            $row['sex'] = $da[3] == 1 || $da[3] == 0 ? $da[3] : ($da[3] == '男' ? 1 : 0);
            $row['phone'] = $da[4];
            $row['is_success'] = 0;
            $row['address'] = $da[5] ? $da[5] : '未知';
            $row['zuoji'] = $da[6] ? $da[6] : '';
            $row['keyword'] = $da[7] ? $da[7] : '';
            $row['des'] = $da[8] ? $da[8] : '';
            $row['from'] = $da[9] ? $da[9] : '未知';
            $row['wx'] = $da[10] ? $da[10] : '';
            $row['qq'] = $da[11] ? $da[11] : '';
            $row['email'] = $da[12] ? $da[12] : '';
            $row['web'] = $da[13] ? $da[13] : '';
            $row['company'] = $da[14] ? $da[14] : '未知';
            $row['comp_type'] = $da[15] ? $da[15] : '未知';
            $row['comp_scale'] = $da[16] ? $da[16] : '未知';
            $row['comp_skill'] = $da[17] ? $da[17] : '未知';
            $row['content'] = $da[18] ? $da[18] : '';
            $row['atime'] = date('Y-m-d H:i:s');
            $rs[] = $row;
        }
        return $rs;
    }

    /**
     * 成功签订协议
     * @param type $cid
     */
    public function successSign($cid, $uid) {
        return $this->customMd->successSign($cid, $uid);
    }

    /**
     * 签订协议
     * @param type $cid
     */
    public function failSign($cid, $uid) {
        return $this->customMd->failSign($cid, $uid);
    }

    /**
     * 把正在追踪的客户释放到公共资源池,根据时间
     * @param type $time 小时
     */
    public function resetTimeCustom($time) {
        if (intval($time) <= 0) {
            return false;
        }
        $second = $time * 60 * 60;
        $customs = $this->customMd->getTimeFollowCustom($second);
        foreach ($customs as $cus) {
            $this->setCustomUid($cus['id'], 0);
            $this->addFollow($cus['id'], 0, $cus['uid'], '系统自动释放到公共资源池');
        }
        return true;
    }

    public function signRank($time = null) {
        return $this->customMd->signRank($time);
    }

    public function mouthSignRand() {
        return $this->signRank(date('Y-m'));
    }

    public function followRank($time = null) {
        return $this->followMd->followRank($time);
    }

    public function mouthFollowRand() {
        return $this->followRank(date('Y-m'));
    }

    public function bigShark() {
        return $this->customMd->bigShark();
    }

}
