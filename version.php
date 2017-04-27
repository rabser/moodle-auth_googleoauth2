<?php
// This file is part of Oauth2 authentication plugin for Moodle.
//
// Oauth2 authentication plugin for Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Oauth2 authentication plugin for Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Oauth2 authentication plugin for Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Google/Facebook/Messanger Oauth2 authentication plugin version specification.
 *
 * @package    auth
 * @subpackage googleoauth2
 * @copyright  2012 Jerome Mouneyrac {@link http://www.moodleitandme.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$plugin->version  = 2017042701;
$plugin->requires = 2014051200;   // Requires Moodle 2.7 or later.
$plugin->release = '2.3.1 (Build: 2017042701)';
$plugin->maturity = MATURITY_STABLE;             // This version's maturity level.
$plugin->component = 'auth_googleoauth2'; // Declare the type and name of this plugin.

