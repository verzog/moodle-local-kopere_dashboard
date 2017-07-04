<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * @created    16/05/17 04:10
 * @package    local_kopere_dashboard
 * @copyright  2017 Eduardo Kraus {@link http://eduardokraus.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_kopere_dashboard;

use local_kopere_dashboard\util\Json;

class Enrol {
    public function lastEnrol() {
        global $DB;

        $sql
            = "SELECT ue.id, ra.roleid, e.courseid, c.fullname, ue.userid, ue.timemodified, ue.timeend, ue.status, e.enrol
                 FROM {user_enrolments} ue
                 JOIN {role_assignments} ra ON ue.userid = ra.userid
                 JOIN {enrol} e             ON e.id = ue.enrolid
                 JOIN {context} ctx         ON ctx.instanceid = e.courseid
                 JOIN {course} c            ON c.id = e.courseid
                WHERE ra.contextid = ctx.id
             GROUP BY e.courseid, ue.userid
             ORDER BY ue.timemodified DESC
                LIMIT 0, 10";

        return $DB->get_records_sql($sql);
    }

    public function ajaxdashboard() {
        global $DB;

        $courseid = optional_param('courseid', 0, PARAM_INT);

        $sql
            = "SELECT ue.userid AS id, concat(firstname, ' ', lastname) as nome, u.email, ue.status
		         FROM {user_enrolments} ue
                    LEFT JOIN {user} u ON u.id = ue.userid
                    LEFT JOIN {enrol} e ON e.id = ue.enrolid
                    LEFT JOIN {course} c ON c.id = e.courseid
                    LEFT JOIN {course_completions} cc ON cc.timecompleted > 0 AND cc.course = e.courseid and cc.userid = ue.userid
		        WHERE c.id = :id AND u.id IS NOT NULL
		     GROUP BY ue.userid, e.courseid";

        $result = $DB->get_records_sql($sql, array('id' => $courseid));

        Json::encodeAndReturn($result);
    }

}