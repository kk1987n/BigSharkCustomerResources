<?php

namespace Home\Model;

use Think\Model;

class FollowModel extends Model {

    public function addFollow($cid, $status, $uid, $content) {
        $data['cid'] = $cid;
        $data['content'] = $content;
        $status != null ? $data['status'] = $status : null;
        $uid != null ? $data['uid'] = $uid : null;
        return $this->add($data);
    }

    public function followInfo($cid) {
        $list = $this
                ->field('flw.status,flw.content,flw.utime,us.name as uname,ctm.name as cname,fs.name as statusname')
                ->alias('flw')
                ->join(C('DB_PREFIX') . 'custom as ctm on ctm.id=flw.cid', 'left')
                ->join(C('DB_PREFIX') . 'user as us on us.id=flw.uid', 'left')
                ->join(C('DB_PREFIX') . 'follow_status as fs on fs.id=flw.status', 'left')
                ->where(array('flw.cid' => $cid))
                ->order('flw.utime desc')
                ->select();
        return $list;
    }

    public function followRank($time = null) {
        $time == null ?: $where = 'flw.utime>="' . date('Y-m', strtotime($time)) . '"';
        return $this
                        ->alias('flw')
                        ->field('count(*) as cnt,us.`name`')
                        ->join(C('DB_PREFIX') . 'user as us on us.id=flw.uid', 'left')
                        ->where($where)
                        ->group('flw.uid')
                        ->order('cnt desc')
                        ->limit(0, 10)
                        ->select();
    }

}
