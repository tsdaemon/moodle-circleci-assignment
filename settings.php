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

$settings->add(new admin_setting_configtext('assignsubmission_circleci/aws_region',
                  new lang_string('aws_region', 'assignsubmission_circleci'),
                  new lang_string('aws_region_help', 'assignsubmission_circleci'), 'us-east-2'));

$settings->add(new admin_setting_configtext('assignsubmission_circleci/aws_key',
                  new lang_string('aws_key', 'assignsubmission_circleci'),
                  new lang_string('aws_key_help', 'assignsubmission_circleci'), ''));

$settings->add(new admin_setting_configtext('assignsubmission_circleci/aws_secret',
                  new lang_string('aws_secret', 'assignsubmission_circleci'),
                  new lang_string('aws_secret_help', 'assignsubmission_circleci'), ''));

$settings->add(new admin_setting_configtext('assignsubmission_circleci/aws_bucket',
                  new lang_string('aws_bucket', 'assignsubmission_circleci'),
                  new lang_string('aws_bucket_help', 'assignsubmission_circleci'), 'moodle-circleci'));
