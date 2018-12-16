<?php

defined('MOODLE_INTERNAL') || die();


/**
 * Code run after the mod_circleci_assignment module database tables have been created.
 * @return bool
 */
function xmldb_assignment_install() {
    global $DB;

    return true;
}
