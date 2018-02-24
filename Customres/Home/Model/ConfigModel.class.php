<?php

namespace Home\Model;

use Think\Model;

class ConfigModel extends Model {

    public function getConfig() {
        return $this->find();
    }

    public function setConfig($config) {
        return $this->where(array('id' => 1))->save(array('config' => $config));
    }

}
