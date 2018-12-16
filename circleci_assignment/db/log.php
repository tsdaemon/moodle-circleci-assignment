<?php

/**
 * Definition of log events
 *
 * @package    mod_circleci_assignment
 * @category   log
 */

defined('MOODLE_INTERNAL') || die();

$logs = array(
    array('module'=>'circleci_assignment', 'action'=>'view', 'mtable'=>'circleci_assignment', 'field'=>'name'),
    array('module'=>'circleci_assignment', 'action'=>'add', 'mtable'=>'circleci_assignment', 'field'=>'name'),
    array('module'=>'circleci_assignment', 'action'=>'update', 'mtable'=>'circleci_assignment', 'field'=>'name'),
    array('module'=>'circleci_assignment', 'action'=>'view submission', 'mtable'=>'circleci_assignment', 'field'=>'name'),
    array('module'=>'circleci_assignment', 'action'=>'upload', 'mtable'=>'circleci_assignment', 'field'=>'name'),
);
