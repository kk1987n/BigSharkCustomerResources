<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Home\Controller;

use Common\Common\Page;
use Home\Service\Config;
use Home\Service\Custom;
use Think\Controller;
use function session;

/**
 * Description of Base
 *
 * @author Administrator
 */
class Base extends Controller {

    public $config;

    //put your code here
    public function __construct() {
        parent::__construct();
        $this->init();
    }

    public function init() {
        if (!$this->checkLogin()) {
            return false;
        }
        $this->assign('web_title', '客户资源管理');

        $configSvc = new Config();
        $this->config = $configSvc->getConfig();
        unset($configSvc);
        $this->updateCostom();
    }

    public function checkLogin() {
        if (!session('uid')) {
            session(null);
            $this->redirect('Home/Login/index');
        }
        if (!session('ltime')) {
            session(null);
            $this->redirect('Home/Login/index');
        }
        return true;
    }

    public function updateCostom() {
        $cusSvc = new Custom();
        if ($this->config['resetTimeCustom']) {
            $cusSvc->resetTimeCustom($this->config['resetTimeCustom']);
        }
    }

    /**
     * 创建page
     * @param type $count
     * @return Page
     */
    public function c_Page($count, $pg_cnt = 10, $p_char = 'p') {
        $Page = new Page($count, $p_char, $pg_cnt); // 实例化分页类 传入总记录数和每页显示的记录数
//        $Page->setConfig('header', '条记录');
//        $Page->setConfig('theme', '<li><a>%totalRow% %header%</a></li> <li>%upPage%</li> <li>%downPage%</li> <li>%first%</li>  <li>%prePage%</li>  <li>%linkPage%</li>  <li>%nextPage%</li> <li>%end%</li> '); //(对thinkphp自带分页的格式进行自定义)
        return $Page;
    }

}
