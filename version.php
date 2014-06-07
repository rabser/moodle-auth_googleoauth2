<?php
// This file is not a part of Moodle - http://moodle.org/
// This is a none core contributed module.
//
// This is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// This is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// The GNU General Public License
// can be see at <http://www.gnu.org/licenses/>.

/**
 * Google/Facebook/Messanger Oauth2 authentication plugin version specification.
 *
 * @package    auth
 * @subpackage googleoauth2
 * @copyright  2012 Jerome Mouneyrac {@link http://www.moodleitandme.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$plugin->version  = 2014060700;
$plugin->requires = 2013102500;   // Requires Moodle 2.6 or later
$plugin->release = '1.5 (Build: 2013110100)';
$plugin->maturity = MATURITY_STABLE;             // this version's maturity level
