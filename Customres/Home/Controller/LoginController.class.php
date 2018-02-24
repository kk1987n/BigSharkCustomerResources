<?php

namespace Home\Controller;

use Home\Service\User;
use Think\Controller;
use const ACTION_NAME;
use const IS_AJAX;
use const IS_CLI;
use function C;
use function getRandChar;
use function session;

/**
 * Description of IndexController
 *
 * @author Administrator
 */
class LoginController extends Controller {

    private $svc;

    public function __construct() {
        parent::__construct();
        if (ACTION_NAME == 'logout') {
            $this->logout();
        }
        if (session('uid') > 0) {
            $this->redirect('Home/Index/index');
        }
        $this->svc = new User();
    }

    public function token($time, $rd = null) {
        if (!$rd) {
            $rd = getRandChar(2);
        }
        return substr(md5($time . md5(C('PUB_SALT') . $rd)), 0, 30) . $rd;
    }

    public function checkToken($time, $token) {
        $rd = substr($token, 30);
        if ($token == $this->token($time, $rd)) {
            return true;
        }
        return false;
    }

    public function index() {
        $time = time();
        $this->assign('time', $time);
        $this->assign('token', $this->token($time));
        $this->display();
    }

    public function logout() {
        session(null);
        $this->redirect('Home/Login/index');
    }

    public function doLogin() {
        if (IS_AJAX) {
            if (!$this->checkToken(I('post.time'), I('post.token'))) {
                $this->ajaxReturn(array('status' => -3, 'msg' => '页面已过期，请刷新后重试'));
            }
            $account = I('post.username');
            $pwdHash = I('post.pwdHash');
            if ($account && $pwdHash) {
                $user = $this->svc->userLogin($account, $pwdHash);
                if ($user['status'] == 1) {
                    session(null);
                    session('uid', $user['user']['id']);
                    session('uname', $user['user']['name']);
                    session('ltime', $user['user']['ltime']);
                    $this->ajaxReturn(array('status' => 1, 'msg' => '登录成功'));
                }
            }
        }
        $this->ajaxReturn(array('status' => -1, 'msg' => '登录失败（账号/密码错误）'));
    }

}
