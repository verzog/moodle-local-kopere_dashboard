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
 * User: Eduardo Kraus
 * Date: 04/07/17
 * Time: 08:55
 */

namespace local_kopere_dashboard\util;

defined('MOODLE_INTERNAL') || die();

/**
 * Class navigation
 *
 * @package local_kopere_dashboard\util
 */
class navigation {
    /**
     * @param     $atual_page
     * @param     $total_registers
     * @param     $base_url
     * @param int $perpag
     */
    public static function create($atual_page, $total_registers, $base_url, $perpag = 20) {
        $count_pages = intval($total_registers / $perpag);

        if (($total_registers % $perpag) != 0) {
            $count_pages += 1;
        }

        echo "<span class=\"pagination-info\">" . get_string_kopere('navigation_page',
                array('atualPage' => $atual_page, 'countPages' => $count_pages)) . "</span>";

        echo "<ul class=\"pagination\">";
        if ($atual_page != 1) {
            echo "<li><a href=\"{$base_url}1\" >«</a></li>";
        }
        $i = $atual_page - 4;
        if ($i < 1) {
            $i = 1;
        }
        if ($i != 1) {
            echo "<li><span>...</span></li>";
        }

        $loop = 0;
        for (; $i <= $count_pages; $i++) {
            if ($i == $atual_page) {
                echo "<li class=\"active\"><span>{$i}</span></li>";
            } else {
                echo "<li><a href=\"{$base_url}{$i}\">{$i}</a></li>";
            }

            $loop++;
            if ($loop == 7) {
                if ($i != $count_pages) {
                    echo "<span>...</span></li>";
                }
                break;
            }
        }
        if (($atual_page) != $count_pages && $count_pages > 1) {
            echo "<li><a href=\"{$base_url}{$count_pages}\">»</a></li>";
        }
        echo "</ul>";
    }
}