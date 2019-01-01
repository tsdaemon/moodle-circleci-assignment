<?php
/**
 * This file defines the admin settings for this plugin
 *
 * @package   assignsubmission_circleci
 * @copyright 2018 tsdaemon
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$settings->add(new admin_setting_configcheckbox('assignsubmission_circleci/default',
                   new lang_string('default', 'assignsubmission_circleci'),
                   new lang_string('default_help', 'assignsubmission_circleci'), 0));
