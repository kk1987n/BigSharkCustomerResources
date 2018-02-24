<?php

namespace Home\Controller;

use Home\Service\Custom;

/**
 * Description of IndexController
 *
 * @author Administrator
 */
class IndexController extends Base {

    private $cusSvc;

    public function __construct() {
        parent::__construct();
        $this->cusSvc = new Custom();
    }

    //put your code here
    public function index() {
        $this->assign('signRank', $this->cusSvc->signRank());
        $this->assign('mouthSignRank', $this->cusSvc->mouthSignRand());
        $this->assign('followRank', $this->cusSvc->followRank());
        $this->assign('mouthFollowRand', $this->cusSvc->mouthFollowRand());
        $this->assign('bigShark', $this->cusSvc->bigShark());
        $this->display();
    }

}
