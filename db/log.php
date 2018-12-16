<?php
/**
 * Definition of log events
 *
 * @package   mod_circleci_assign
 * @copyright 2012 NetSpot {@link http://www.netspot.com.au}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$logs = array(
    array('module'=>'circleci_assign', 'action'=>'add', 'mtable'=>'circleci_assign', 'field'=>'name'),
    array('module'=>'circleci_assign', 'action'=>'delete mod', 'mtable'=>'circleci_assign', 'field'=>'name'),
    array('module'=>'circleci_assign', 'action'=>'download all submissions', 'mtable'=>'circleci_assign', 'field'=>'name'),
    array('module'=>'circleci_assign', 'action'=>'grade submission', 'mtable'=>'circleci_assign', 'field'=>'name'),
    array('module'=>'circleci_assign', 'action'=>'lock submission', 'mtable'=>'circleci_assign', 'field'=>'name'),
    array('module'=>'circleci_assign', 'action'=>'reveal identities', 'mtable'=>'circleci_assign', 'field'=>'name'),
    array('module'=>'circleci_assign', 'action'=>'revert submission to draft', 'mtable'=>'circleci_assign', 'field'=>'name'),
    array('module'=>'circleci_assign', 'action'=>'set marking workflow state', 'mtable'=>'circleci_assign', 'field'=>'name'),
    array('module'=>'circleci_assign', 'action'=>'submission statement accepted', 'mtable'=>'circleci_assign', 'field'=>'name'),
    array('module'=>'circleci_assign', 'action'=>'submit', 'mtable'=>'circleci_assign', 'field'=>'name'),
    array('module'=>'circleci_assign', 'action'=>'submit for grading', 'mtable'=>'circleci_assign', 'field'=>'name'),
    array('module'=>'circleci_assign', 'action'=>'unlock submission', 'mtable'=>'circleci_assign', 'field'=>'name'),
    array('module'=>'circleci_assign', 'action'=>'update', 'mtable'=>'circleci_assign', 'field'=>'name'),
    array('module'=>'circleci_assign', 'action'=>'upload', 'mtable'=>'circleci_assign', 'field'=>'name'),
    array('module'=>'circleci_assign', 'action'=>'view', 'mtable'=>'circleci_assign', 'field'=>'name'),
    array('module'=>'circleci_assign', 'action'=>'view all', 'mtable'=>'course', 'field'=>'fullname'),
    array('module'=>'circleci_assign', 'action'=>'view confirm submit assignment form', 'mtable'=>'circleci_assign', 'field'=>'name'),
    array('module'=>'circleci_assign', 'action'=>'view grading form', 'mtable'=>'circleci_assign', 'field'=>'name'),
    array('module'=>'circleci_assign', 'action'=>'view submission', 'mtable'=>'circleci_assign', 'field'=>'name'),
    array('module'=>'circleci_assign', 'action'=>'view submission grading table', 'mtable'=>'circleci_assign', 'field'=>'name'),
    array('module'=>'circleci_assign', 'action'=>'view submit assignment form', 'mtable'=>'circleci_assign', 'field'=>'name'),
    array('module'=>'circleci_assign', 'action'=>'view feedback', 'mtable'=>'circleci_assign', 'field'=>'name'),
    array('module'=>'circleci_assign', 'action'=>'view batch set marking workflow state', 'mtable'=>'circleci_assign', 'field'=>'name'),
);
