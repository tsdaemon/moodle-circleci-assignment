<?php
/**
 * Post-install code for the submission_circleci module.
 *
 * @package   assignsubmission_circleci
 * @copyright 2018 tsdaemon
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();


/**
 * Code run after the assignsubmission_circleci module database tables have been created.
 * Moves the plugin to the top of the list (of 4)
 * @return bool
 */
function xmldb_assignsubmission_circleci_install() {
    global $CFG;

    // Set the correct initial order for the plugins.
    require_once($CFG->dirroot . '/mod/assign/adminlib.php');
    $pluginmanager = new assign_plugin_manager('assignsubmission');

    $pluginmanager->move_plugin('circleci', 'up');
    $pluginmanager->move_plugin('circleci', 'up');
    $pluginmanager->move_plugin('circleci', 'up');

    return true;
}
