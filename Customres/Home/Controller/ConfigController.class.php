<?php

namespace Home\Controller;

use Home\Service\Config;
use Home\Service\User;

/**
 * Description of IndexController
 *
 * @author Administrator
 */
class ConfigController extends Base {

    private $userSvc;
    private $configSvc;

    public function __construct() {
        parent::__construct();
        $this->userSvc = new User();
        $this->configSvc = new Config();
    }

    //put your code here
    public function index() {
        $this->assign('uList', $this->userSvc->getUserList());
        $this->display();
    }

    public function addUser() {
        $data = I('post.');
        if ($this->userSvc->addUser($data)) {
            $this->ajaxReturn(array('status' => 1, 'msg' => '成功'));
        } else {
            $this->ajaxReturn(array('status' => -1, 'msg' => '失败'));
        }
    }

    public function resetPwd() {
        $uid = I("post.uid");
        $rs = $this->userSvc->resetPwd($uid);
        if ($rs) {
            $this->ajaxReturn(array('status' => 1, 'msg' => '成功,新密码是:' . $rs));
        } else {
            $this->ajaxReturn(array('status' => -1, 'msg' => '失败'));
        }
    }

    public function editPwd() {
        $this->display();
    }

    public function doEditPwd() {
        $nPwd = I('post.nPwd');
        if ($nPwd && $this->userSvc->resetPwd(session('uid'), $nPwd)) {
            session(null);
            $this->success('成功');
        } else {
            $this->error('失败');
        }
    }

    /**
     * 更新用户追踪数量
     */
    public function followCnt() {
        $uid = I('post.uid');
        $cnt = I('post.cnt');
        if ($this->userSvc->followCnt($uid, intval($cnt))) {
            $this->ajaxReturn(array('status' => 1, 'msg' => '成功'));
        }
        $this->ajaxReturn(array('status' => -1, 'msg' => '失败'));
    }

    public function config() {
        $config = $this->configSvc->getConfig();
        $this->assign('config', $config);
        $this->display();
    }

    public function setConfig() {
        $data = I('post.');
        if ($this->configSvc->setConfig($data)) {
            $this->success('成功');
        } else {
            $this->error('失败');
        }
    }

}
