<?php

namespace Home\Model;

use Think\Model;

class StatusModel extends Model {

    public function dlist() {
        return $this->select();
    }

}
