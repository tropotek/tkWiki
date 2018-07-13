<?php
namespace App\Controller;

/**
 * @author Michael Mifsud <info@tropotek.com>
 * @link http://www.tropotek.com/
 * @license Copyright 2016 Michael Mifsud
 */
abstract class Iface extends \Bs\Controller\Iface
{

    /**
     * @return \App\Config
     */
    public function getConfig()
    {
        return parent::getConfig();
    }


}