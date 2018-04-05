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
 * Add olw form
 *
 * @package    mod
 * @subpackage olw
 * @copyright  2006 Jamie Pratt
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

require_once ($CFG->dirroot.'/course/moodleform_mod.php');

class mod_olw_mod_form extends moodleform_mod {

    function definition() {
		GLOBAL $DB;
        $mform = $this->_form;
		
        $mform->addElement('header', 'generalhdr', get_string('general'));
        /*
		$this->add_intro_editor(true, get_string('olwtext', 'olw'));
		$mform->removeElement('introeditor');
		*/
        $attributes=array('size'=>'200', 'style' => 'width:40%');
		$mform->addElement('text', 'link', get_string('enterlink', 'olw'), $attributes);
		
		if(isset($_REQUEST['update'])) {
			$count = $DB->count_records('course_modules', array('id' => $_REQUEST['update']));
			if($count > 0) {
				$instance = $DB->get_record('course_modules', array('id' => $_REQUEST['update']), $fields='instance', $strictness='MUST_EXIST');
				$link = $DB->get_record('olw', array('id' => $instance->instance), 'intro, materialid', $strictness='MUST_EXIST');
				$matID = $link->materialid;
				
				//echo "<pre>".print_r ($link->intro)."</pre>";
				//echo "<pre>".print_r ($link)."</pre>";
				//echo "<pre>".print_r ($matID)."</pre>";
				
				// Sammlung oder Material?
				if(strpos($link->intro, "collection") > 0) {
					$domain = "https://openlearnware.tu-darmstadt.de/collection/";
					$feed_url = "https://openlearnware.tu-darmstadt.de/olw-rest-db/api/collection-detailview/".$matID;
				}
				else {
					$domain = "https://openlearnware.tu-darmstadt.de/resource/";
					$feed_url = "https://openlearnware.tu-darmstadt.de/olw-rest-db/api/resource-detailview/".$matID;
				}
				
				// Wir filtern uns den Namen aus der olw-rest-db
				$c = new curl(array('cache'=>true, 'module_cache'=>'repository'));
				$content = $c->get($feed_url);
				$elements = json_decode($content, true);
				
				$name = $elements["name"];
				//echo "<pre>".print_r ($name)."</pre>";
					
				//Dann ersetzen wir alle Leerzeichen mit Bindestrichen
				$name_new = str_replace ( " " , "-" , $name );
				//echo "<pre>".print_r ($name_new)."</pre>";
					
				//.. und f�gen die ID hinter den Namen ein
				$nameID = $name_new."-".$matID;
				//echo "<pre>".print_r ($nameID)."</pre>";
				
				// Hier wird der Link rekonstruiert, der dann bei der Bearbeitung des Moduls erscheint
				$mform->setDefault('link', $domain.$nameID);
			}
		}
		$mform->addRule('link', get_string('error'), 'required', 'extra', 'server', false, false);
		$mform->addHelpButton('link', 'link', 'olw');
		$mform->setType('link', PARAM_TEXT);
        $this->standard_coursemodule_elements();
			

//-------------------------------------------------------------------------------
// buttons
        $this->add_action_buttons(true, false, null);

    }
	function validation($data, $files) {
        $errors = parent::validation($data, $files);
		$explosion = explode("/",$data['link']);
		
		if(!isset($explosion[4])) {
			$errors['option[0]'] = get_string('wronglink', 'olw');
        }

        return $errors;
    }

}
