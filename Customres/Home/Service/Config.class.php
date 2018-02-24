<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Home\Service;

use Home\Model\ConfigModel;

/**
 * Description of Config
 *
 * @author Administrator
 */
class Config {

    private $configMd;

    public function __construct() {
        $this->configMd = new ConfigModel();
    }

    public function getConfig() {
        $config = $this->configMd->getConfig();
        return json_decode($config['config'], TRUE);
    }

    public function setConfig($config) {
        $configDb = $this->getConfig();
        return $this->configMd->setConfig(json_encode(array_merge(is_array($configDb) ? $configDb : array(), is_array($config) ? $config : array())));
    }

}
