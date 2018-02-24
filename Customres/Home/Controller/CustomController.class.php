<?php

namespace Home\Controller;

use Home\Service\Custom;

/**
 * Description of IndexController
 *
 * @author Administrator
 */
class CustomController extends Base {

    private $cusSvc;

    public function __construct() {
        parent::__construct();
        $this->cusSvc = new Custom();
    }

    //put your code here
    public function pool() {
        $count = $this->cusSvc->getFreeCustomCnt();
        $page = $this->c_Page($count);
        $customs = $this->cusSvc->getFreeCustomList($page->firstRow, $page->listRows);
        $this->assign("customs", $customs);
        $this->assign('show', $page->show());
        $this->display();
    }

    public function followCustom() {
        $cid = I('post.cid');
        $uid = session('uid');
        $rs = $this->cusSvc->startFollowCustom($cid, $uid);
        if ($rs['status'] == 1) {
            $this->ajaxReturn(array('status' => 1, 'msg' => '成功'));
        }
        $this->ajaxReturn(array('status' => -1, 'msg' => '失败:' . $rs['msg']));
    }

    public function dropCustomFollow() {
        $cid = I('post.cid');
        $uid = session('uid');
        if ($cid && $uid && $this->cusSvc->dropCustomFollow($cid, $uid)) {
            $this->ajaxReturn(array('status' => 1, 'msg' => '成功'));
        }
        $this->ajaxReturn(array('status' => -1, 'msg' => '失败'));
    }

    public function userCustom() {
        $status = I('get.status');
        $this->assign("statusGet", $status);
        $count = $this->cusSvc->userCustomCnt(session('uid'), $status);
        $page = $this->c_Page($count);
        $userCustom = $this->cusSvc->userCustomLst(session('uid'), $status, $page->firstRow, $page->listRows);
        $this->assign('show', $page->show());
        $this->assign("userCustom", $userCustom);
        $this->assign("followStatus", $this->cusSvc->getFollowStatus());
        $this->display();
    }

    /**
     * 客户跟踪详情,带步骤,包括创建者
     */
    public function customFollowInfo() {
        $cid = I('get.cid');
        if ($cid) {
            $custom = $this->cusSvc->customInfo($cid);
            $this->assign("custom", $custom);
            $follows = $this->cusSvc->customFollowInfo($cid);
            $this->assign("follows", $follows);
            $this->display();
        } else {
            exit('客户不存在');
        }
    }

    /**
     * 客户详情
     */
    public function customInfo() {
        $cid = I('get.cid');
        if ($cid) {
            $custom = $this->cusSvc->customInfo($cid);
            $this->assign("custom", $custom);
            $this->display();
        } else {
            exit('客户不存在');
        }
    }

    public function addFollow() {
        $data = I('post.');
        $rs = $this->cusSvc->addFollow($data['cid'], $data['status'], session('uid'), $data['content']);
        if ($rs) {
            if ($data['status'] == 999) {//999是固定值,代表签订成功
                $this->cusSvc->successSign($data['cid'], session('uid'));
            } elseif ($data['status'] == 1000) {
                $this->cusSvc->failSign($data['cid'], session('uid'));
            }
            $this->ajaxReturn(array('status' => 1, 'msg' => '成功'));
        } else {

            $this->ajaxReturn(array('status' => -1, 'msg' => '失败'));
        }
    }

    /**
     * 新建客户
     */
    public function addCustom() {
        $this->display();
    }

    /**
     * 新增1个客户
     */
    public function doAddCustom() {
        $data = I('post.');
        if ($this->cusSvc->addCustom($data, session('uid'))) {
            $this->success('成功');
        } else {
            $this->error('失败');
        }
    }

    public function successList() {
        $uid = I('get.uid');
        $count = $this->cusSvc->getSuccessCnt($uid ? $uid : 0);
        $page = $this->c_Page($count);
        $customs = $this->cusSvc->getSuccessList($uid ? $uid : 0, $page->firstRow, $page->listRows);
        $this->assign("customs", $customs);
        $this->assign('show', $page->show());
        $this->display();
    }

    /**
     * 上传表格新增大量用户
     */
    public function uploadExcelData() {
        set_time_limit(0);
        $config = array(
            'maxSize' => 10 * 1024 * 1024, //10M大小
            'rootPath' => './Public/Upload/Excel/' . date('Ymd') . '/', //保存根路径
            'saveName' => array('uniqid', ''),
            'subName' => array('uniqid', ''),
            'exts' => array('xls'),
            'autoSub' => true,
        );
        if (!file_exists($config['rootPath'])) {
            mkdir($config['rootPath'], 0777, true);
        }
        $upload = new \Think\Upload($config);
        $rs = $upload->upload();
        if ($rs[0]['savename']) {//成功
            $data = $this->readExcel($config['rootPath'] . $rs[0]['savepath'] . $rs[0]['savename']);
            if ($data['status'] == 1) {
                $addExcelCustom = $this->cusSvc->addExcelCustom($data['data'], session('uid'));
                $this->ajaxReturn(array('status' => 1, 'msg' => '成功'));
            } else {
                $this->ajaxReturn(array('status' => -1, 'msg' => '数据出错-' . $data['msg']));
            }
        } else {
            $this->ajaxReturn(array('status' => -1, 'msg' => '上传失败'));
        }
    }

    private function readExcel($excel) {
        $letter = array(
            'A', 'B', 'C', 'D', 'E',
            'F', 'G', 'H', 'I', 'J',
            'K', 'L', 'M', 'N', 'O',
            'P', 'Q', 'R', 'S');
        Vendor('PHPExcel');
        $PHPReader = new \PHPExcel_Reader_Excel5();
        if (!$PHPReader->canRead($excel)) {
            $this->ajaxReturn(array('status' => -1, 'msg' => 'Excel读取失败'));
        }
        $PHPExcel = $PHPReader->load($excel); //读取文件
        $currentSheet = $PHPExcel->getSheet(0); //读取第一个工作簿
        $allRow = $currentSheet->getHighestRow(); // 所有行数
        if ($allRow > 5000) {
            return array('status' => -1, 'msg' => '请不要超过5000行');
        }
        $data = array(); //下面是读取想要获取的列的内容
        for ($rowIndex = 2; $rowIndex <= $allRow; $rowIndex++) {
            $row = array();
            for ($i = 0; $i < 19; $i++) {//一共读取19个字段
                $row[$i] = $currentSheet->getCell($letter[$i] . $rowIndex)->getValue();
            }
            if ($row = $this->checkExcelData($row)) {
                $data[] = $row;
            } else {
                return array('status' => -1, 'msg' => '行解析错误:' . $rowIndex);
            }
        }
        return array('status' => 1, 'data' => $data);
    }

    private function checkExcelData($row) {
        $row[0] = intval($row[0]) ? intval($row[0]) : 0;
        return $row;
    }

    /**
     * 获取上传用的表格的模板
     */
    public function getExcelTemp() {
        Vendor('PHPExcel');
        $excel = new \PHPExcel(); /////初始化引入的方法
//Excel表格式,这里简略写了8列
        $letter = array(
            'A', 'B', 'C', 'D', 'E',
            'F', 'G', 'H', 'I', 'J',
            'K', 'L', 'M', 'N', 'O',
            'P', 'Q', 'R', 'S');
        //表头数组
        $tableheader = array(
            '招商经理ID(填入即立刻跟踪)',
            '客户姓名',
            '年龄',
            '性别(1男0女)',
            '手机固话',
            '地址',
            '座机号',
            '关键词',
            '一句话描述',
            '来源(百度/电话营销/介绍等汉字)',
            '微信号',
            'qq',
            'email',
            '网址',
            '公司名',
            '公司类型(政府/广告/媒体等)',
            '规模(人数)',
            '主营业务',
            '额外重要信息');
        //填充表头信息
        for ($i = 0; $i < count($tableheader); $i++) {
            $excel->getActiveSheet()->setCellValue("$letter[$i]1", "$tableheader[$i]");
        }
        //表格数组
        $data = array(
            array(
                '1',
                '阿鑫',
                '18',
                '1',
                '13112345678',
                '郑州',
                '4001234567',
                'PHP,开发者',
                '热爱技术的开发者',
                '百度推广',
                '13112345678',
                '13112345',
                '13112345@com',
                'http://www.xxx.com',
                '加里敦网',
                '互联网',
                '50',
                '广告',
                '这家公司对新技术很感兴趣,请着重追踪-请删除本行'),
        );
        //填充表格信息
        for ($i = 2; $i <= count($data) + 1; $i++) {
            $j = 0;
            foreach ($data[$i - 2] as $key => $value) {
                $excel->getActiveSheet()->setCellValue("$letter[$j]$i", "$value");
                $j++;
            }
        }
        //创建Excel输入对象
        $write = new \PHPExcel_Writer_Excel5($excel);
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
        header("Content-Type:application/force-download");
        header("Content-Type:application/vnd.ms-execl");
        header("Content-Type:application/octet-stream");
        header("Content-Type:application/download");
        header('Content-Disposition:attachment;filename="客户资源模板.xls"');
        header("Content-Transfer-Encoding:binary");
        $write->save('php://output');
    }

}
