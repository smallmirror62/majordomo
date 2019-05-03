<?php

$ot=$this->object_title;
$linked_room=$this->getProperty('linkedRoom');

$tm=time();
$this->setProperty('updated', $tm);

if ($this->getProperty('alive') == 0) {
 $this->setProperty('alive', 1);
}


$alive_timeout=(int)$this->getProperty('aliveTimeout')*60*60;
if (!$alive_timeout) {
    $alive_timeout=2*24*60*60; // 2 days alive timeout by default
}

setTimeout($ot.'_alive_timer', 'setGlobal("'.$ot.'.alive", 0);', $alive_timeout);

if ($linked_room && $this->getProperty('isActivity')) {
    if (getGlobal('NobodyHomeMode.active')) {
        callMethodSafe('NobodyHomeMode.deactivate');
    }
    $nobodyhome_timeout = 1 * 60 * 60;
    if (defined('SETTINGS_BEHAVIOR_NOBODYHOME_TIMEOUT')) {
        $nobodyhome_timeout = SETTINGS_BEHAVIOR_NOBODYHOME_TIMEOUT * 60;
    }
    if ($nobodyhome_timeout) {
        setTimeOut("nobodyHome", "callMethodSafe('NobodyHomeMode.activate');", $nobodyhome_timeout);
    }
    if ($linked_room) {
        callMethodSafe($linked_room.'.onActivity', array('sensor'=>$ot));
    }
}

$this->callMethod('valueUpdated');
$this->callMethod('setUpdatedText');
