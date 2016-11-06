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
 * Library of functions and constants for module olw
 *
 * @package    mod
 * @subpackage olw
 * @copyright  1999 onwards Martin Dougiamas  {@link http://moodle.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

/** olw_MAX_NAME_LENGTH = 50 */
define("olw_MAX_NAME_LENGTH", 50);

/**
 * @uses olw_MAX_NAME_LENGTH
 * @param object $olw
 * @return string
 */
function get_olw_name($olw) {
    $name = strip_tags(format_string($olw->intro,true));
    if (textlib::strlen($name) > olw_MAX_NAME_LENGTH) {
        $name = textlib::substr($name, 0, olw_MAX_NAME_LENGTH)."...";
    }

    if (empty($name)) {
        // arbitrary name
        $name = get_string('modulename','olw');
    }

    return $name;
}
/**
 * Given an object containing all the necessary data,
 * (defined by the form in mod_form.php) this function
 * will create a new instance and return the id number
 * of the new instance.
 *
 * @global object
 * @param object $olw
 * @return bool|int
 */

function getExtensionsForNumbers($numbers) {
		$Extensions = array();
		foreach($numbers as &$value) {
			// alle MP4
			if($value == 1 || $value == 2 || $value == 4 || $value == 9 || $value == 90 || $value == 3) {
				$a = $value.".mp4";
				array_push($Extensions,$a);
			}
			// alle MP3
			if($value == 7) {
				$a = $value.".mp3";
				array_push($Extensions,$a);
			}
			// alle OGG
			if($value == 8) {
				$a = $value.".ogg";
				array_push($Extensions,$a);
			}
			// alle PDF
			if($value == 13) {
				$a = $value.".pdf";
				array_push($Extensions,$a);
			}
			// alle ZIP
			if($value == 30) {
				$a = $value.".zip";
				array_push($Extensions,$a);
			}
			// alle WEBM
			if($value == 105 || $value == 106 || $value == 205 || $value == 206) {
				$a = $value.".webm";
				array_push($Extensions,$a);
			}
		}

		return $Extensions;

}

/**
 * Diese Funktion muss regelm��ig geupdated werden, wenn sich die CharacteristicTypes �ndern sollten.
 */
function getFileNamesByCharacteristicType($CharType) {
		//PDF
		if($CharType == 1) {
			$arrayOfFilenames = getExtensionsForNumbers(array(13));
		}
		//MP4_HQ
		if($CharType == 2) {
			$arrayOfFilenames = getExtensionsForNumbers(array(2,4,7,8,106));
		}
		//MP3
		if($CharType == 3) {
			$arrayOfFilenames = getExtensionsForNumbers(array(7,8));
		}
		//MP4_LQ
		if($CharType == 4) {
			$arrayOfFilenames = getExtensionsForNumbers(array(1,4,7,8,105));
		}
		//CAM_VIDEO_LQ
		if($CharType == 5) {
			$arrayOfFilenames = getExtensionsForNumbers(array(1,4,7,8,9,30,90,105,205));
		}
		//LPD_VIDEO_LQ
		if($CharType == 6) {
			$arrayOfFilenames = getExtensionsForNumbers(array(1,4,7,8,9,30,90,105,205));
		}
		//LPD_AUDIO
		if($CharType == 7) {
			$arrayOfFilenames = getExtensionsForNumbers(array(7,8,9,30,90,205));
		}
		//CAM_AUDIO
		if($CharType == 8) {
			//$arrayOfFilenames = getExtensionsForNumbers(array(7,8,9,30,90,205));
			$arrayOfFilenames = getExtensionsForNumbers(array(9,90,7,8,30,205));
			//$arrayOfFilenames = getExtensionsForNumbers(array(9,90,205));
		}
		//RAW
		if($CharType == 9) {
			$arrayOfFilenames = getExtensionsForNumbers(array(30));
		}
		//MP4_HD
		if($CharType == 10) {
			$arrayOfFilenames = getExtensionsForNumbers(array(2,3,4,7,8,9,90,106));
		}

		return $arrayOfFilenames;
}

/*function CheckLinksForAvailability($Filenames, $link) {
	foreach($Filenames as $key => $value) {
		$user_agent = "Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:16.0) Gecko/20121026 Firefox/16.0";
		//echo "<pre>".print_r ($Filenames, true)."</pre>";
		$ch = curl_init($link.$value);
		//echo "<pre>".print_r ($link.$value, true)."</pre>";

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$res = curl_exec($ch);
		sleep(1);
		echo "<pre>".print_r ($res, true)."</pre>";
		$info = curl_getinfo($ch);
		curl_close($ch);
		//echo "<pre>".print_r ($info, true)."</pre>";


		//curl_setopt($ch, CURLOPT_HEADER, 1);
		//curl_setopt($ch, CURLOPT_NOBODY, 1);
		//curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
		//curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		//curl_setopt($ch, CURLOPT_HEADER, 1);
		//curl_setopt($ch, CURLOPT_TIMEOUT, 5);
		//curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);

		//$http_statuscode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		//curl_exec($ch);

		//$info = curl_getinfo($ch);
		//curl_close($ch); // close cURL handler

		//echo "<pre>".print_r ($info, true)."</pre>";

		if (empty($info['http_code'])) {
			die("No HTTP code was returned");
		}

		print_r ($http_statuscode);
		if(!preg_match('/^200|301|302$/', $http_statuscode))
		{
			unset($Filenames[$key]);
		}
	}
	return $Filenames;
}*/

function BuildOutput($olw) {
	global $DB;

	$inputLink = $olw->link;
	//$inputLink = strtolower($$olw->link);
	//echo "<pre>".print_r ($inputLink, true)."</pre>";

	$domain = "https://openlearnware.tu-darmstadt.de";

	// Sammlung oder Material?
	$bool = strpos($inputLink, "sammlung"); // Groesser 0, wenn Sammlung
	$bool2 = strpos($inputLink, "collection"); // Groesser 0, wenn Sammlung (collection)

	// Alter Link (https://openlearnware.tu-darmstadt.de/material/2597)
	// oder
	// Neuer Link ohne zusammengesetzten Namen + ID (https://openlearnware.tu-darmstadt.de/#!/resource/2597)
	/*
	if(strpos($inputLink, "#!") == 0
			|| strpos($inputLink, "-") == 0) {

		//material_id aus dem eingegebenen Link filtern
		$explosion = explode("/",$inputLink);
		$nohtml = $inputLink;
		end($explosion);
		$key = key($explosion);

		$material_id = $explosion[$key];
	}
	// Neuer Link (https://openlearnware.tu-darmstadt.de/#!/resource/14-Libration-2597)
	else {
		//material_id aus dem eingegebenen Link filtern
		$explosion = explode("-",$inputLink);
		$nohtml = $inputLink;
		end($explosion);
		$key = key($explosion);

		$material_id = $explosion[$key];
	}*/

	// extrahiert das letzte Pfadsegment
	$last = array_pop(explode("/", $inputLink));
	
	// extrahiert die Material ID 
	$material_id = array_pop(explode("-", $last));

	// Wenn Sammlung, dann..
	if($bool > 0
			|| $bool2 > 0) {
		//name aus der olw-rest-db anhand der material_id filtern
		$feed_url = $domain.'/olw-rest-db/api/collection-detailview/' . $material_id;
		$c = new curl(array('cache'=>true, 'module_cache'=>'repository'));
		$content = $c->get($feed_url);
		$elements = json_decode($content, true);

		$name = $elements['name'];
		//echo "<pre>".print_r ($name, true)."</pre>";
		$name_new = str_replace ( " " , "-" , $name );
		//echo "<pre>".print_r ($name_new, true)."</pre>";

		//Link zusammensetzen
		$ausgabe = "<a href='".$domain."/#!/collection/".$name_new."-".$material_id."' target='_blanc'>OpenLearnWare: ".$name."</a>";

		return array('ausgabe' => $ausgabe, 'name' => $name, 'materialid' => $material_id);
	}

	// Wenn Material, dann..
	else {
		//CharacteristicType aus der olw-rest-db anhand der material_id filtern
		$feed_url = $domain.'/olw-rest-db/api/resource-detailview/' . $material_id;
		$c = new curl(array('cache'=>true, 'module_cache'=>'repository'));
		$content = $c->get($feed_url);
		$elements = json_decode($content, true);
		$name = $elements['name'];
		echo "<pre>".print_r ($name, true)."</pre>";

		$CharacteristicType = $elements["characteristicType"];

		// Thumbnaillink besteht aus uuid, jedes zweite Zeichen ist jedoch ein "/", das hier eingesetzt wird
		$uuid = str_replace ( "-" , "" , $elements["uuid"] );
		$uuid_new = "";
		for ($i = 0; $i < strlen($uuid); $i++) {
			$uuid_new .= substr($uuid, $i, 2);
			$uuid_new .= "/";
			$i++;
		}
		//echo "<pre>".print_r ($uuid_new)."</pre>";

		//aus dem olw-konv-repository holen wir uns anhand der neu zusammen gesetzten uuid ein Array mit den Filenames
		$link = "https://olw-material.hrz.tu-darmstadt.de/olw-konv-repository/material/";
		$link .= $uuid_new;
		//echo "<pre>".print_r ($link, true)."</pre>";

		$Filenames = getFileNamesByCharacteristicType($CharacteristicType);
		//echo "<pre>".print_r ($Filenames)."</pre>";
		//$Filenames = CheckLinksForAvailability($Filenames, $link);
		//echo "<pre>".print_r ($Filenames)."</pre>";


		//Je nachdem, ob ein pdf, eine zip-datei oder ein Video/Audio verlinkt wurde, variiert die Ausgabe (Multimedia wird direkt in Moodle
		//eingebunden. PDF und Zip werden verlinkt.

		// PDF:
		if($CharacteristicType == 1) {
			$filename = str_replace(" ", "_", $name);
			$filename = str_replace(":", "_", $filename);
			$ausgabe = "<a href='".$link."13.pdf?filename=".$filename.".pdf' target='_blanc'>PDF: ".$name."</a><br /><br /><a href='".$nohtml."' target='_blanc'> (".get_string('infoandsource', 'olw').")</a>";
		}
		// ZIP:
		elseif($CharacteristicType == 9) {
			$filename = str_replace(" ", "_", $name);
			$filename = str_replace(":", "_", $filename);
			$ausgabe = "<a href='".$link."30.zip?filename=".$filename.".zip' target='_blanc'>ZIP: ".$name."</a><br /><br /><a href='".$nohtml."' target='_blanc'> (".get_string('infoandsource', 'olw').")</a>";
		}
		// Multimedia (Rest)
		else {
			$ausgabe = $name.":<br />";
			$ausgabe .= "<video width='400' tabindex='0' controls>";

			foreach($Filenames as $key => $value) {
				$ausgabe .= "<source src='".$link.$value."'>";
			}
			$ausgabe .= "</video>";
			$ausgabe .= "<br /><a href='".$nohtml."' target='_blanc'> (".get_string('infoandsource', 'olw').")</a>";
		}

		return array('ausgabe' => $ausgabe, 'name' => $name, 'materialid' => $material_id);
	}
}

/*
<video class="camtasiaplayer-slides camtasiaplayer-media" tabindex="0">
<source src="https://olw-material.hrz.tu-darmstadt.de/olw-konv-repository/material/0b/10/f6/60/32/8f/11/e3/a2/51/00/50/56/bd/73/ad/206.webm"></source>
<source src="https://olw-material.hrz.tu-darmstadt.de/olw-konv-repository/material/0b/10/f6/60/32/8f/11/e3/a2/51/00/50/56/bd/73/ad/205.webm"></source>
<source src="https://olw-material.hrz.tu-darmstadt.de/olw-konv-repository/material/0b/10/f6/60/32/8f/11/e3/a2/51/00/50/56/bd/73/ad/9.mp4"></source>
<source src="https://olw-material.hrz.tu-darmstadt.de/olw-konv-repository/material/0b/10/f6/60/32/8f/11/e3/a2/51/00/50/56/bd/73/ad/90.mp4"></source>
</video>

<video width="400" tabindex="0" controls="">
<source src="https://olw-material.hrz.tu-darmstadt.de/olw-konv-repository/material/0b/10/f6/60/32/8f/11/e3/a2/51/00/50/56/bd/73/ad/7.mp3"></source>
<source src="https://olw-material.hrz.tu-darmstadt.de/olw-konv-repository/material/0b/10/f6/60/32/8f/11/e3/a2/51/00/50/56/bd/73/ad/8.ogg"></source> Geht nicht!
<source src="https://olw-material.hrz.tu-darmstadt.de/olw-konv-repository/material/0b/10/f6/60/32/8f/11/e3/a2/51/00/50/56/bd/73/ad/9.mp4"></source>
<source src="https://olw-material.hrz.tu-darmstadt.de/olw-konv-repository/material/0b/10/f6/60/32/8f/11/e3/a2/51/00/50/56/bd/73/ad/90.mp4"></source>
<source src="https://olw-material.hrz.tu-darmstadt.de/olw-konv-repository/material/0b/10/f6/60/32/8f/11/e3/a2/51/00/50/56/bd/73/ad/205.webm"></source>
</video>
*/
function olw_add_instance($olw) {
	GLOBAL $DB;
	$ausgabe = BuildOutput($olw);

	$params = new stdClass();

	$params->course = $olw->course;
	$params->name = $ausgabe['name'];
	$params->intro = $ausgabe['ausgabe'];
	$params->introformat = 1;
    $params->timemodified = time();
	$params->materialid = $ausgabe['materialid'];
    return $DB->insert_record("olw", $params, true);
}

/**
 * Given an object containing all the necessary data,
 * (defined by the form in mod_form.php) this function
 * will update an existing instance with new data.
 *
 * @global object
 * @param object $olw
 * @return bool
 */
function olw_update_instance($olw) {
    global $DB;

    $ausgabe = BuildOutput($olw);

	$params = new stdClass();
	$params->introeditor = "";
	$params->id = $olw->instance;
	$params->course = $olw->course;
	$params->name = $ausgabe['name'];
	$params->intro = $ausgabe['ausgabe'];
	$params->introformat = 1;
    $params->timemodified = time();
	$params->materialid = $ausgabe['materialid'];

    return $DB->update_record("olw", $params);
}

/**
 * Given an ID of an instance of this module,
 * this function will permanently delete the instance
 * and any data that depends on it.
 *
 * @global object
 * @param int $id
 * @return bool
 */
function olw_delete_instance($id) {
    global $DB;

    if (! $olw = $DB->get_record("olw", array("id"=>$id))) {
        return false;
    }

    $result = true;

    if (! $DB->delete_records("olw", array("id"=>$olw->id))) {
        $result = false;
    }

    return $result;
}

/**
 * Given a course_module object, this function returns any
 * "extra" information that may be needed when printing
 * this activity in a course listing.
 * See get_array_of_activities() in course/lib.php
 *
 * @global object
 * @param object $coursemodule
 * @return cached_cm_info|null
 */
function olw_get_coursemodule_info($coursemodule) {
    global $DB;

    if ($olw = $DB->get_record('olw', array('id'=>$coursemodule->instance), 'id, name, intro, introformat')) {
        if (empty($olw->name)) {
            // olw name missing, fix it
            $olw->name = "olw{$olw->id}";
            $DB->set_field('olw', 'name', $olw->name, array('id'=>$olw->id));
        }
        $info = new cached_cm_info();
        // no filtering hre because this info is cached and filtered later
        $info->content = format_module_intro('olw', $olw, $coursemodule->id, false);
        $info->name  = $olw->name;
        return $info;
    } else {
        return null;
    }
}

/**
 * @return array
 */
function olw_get_view_actions() {
    return array();
}

/**
 * @return array
 */
function olw_get_post_actions() {
    return array();
}

/**
 * This function is used by the reset_course_userdata function in moodlelib.
 *
 * @param object $data the data submitted from the reset course.
 * @return array status array
 */
function olw_reset_userdata($data) {
    return array();
}

/**
 * Returns all other caps used in module
 *
 * @return array
 */
function olw_get_extra_capabilities() {
    return array('moodle/site:accessallgroups');
}

/**
 * @uses FEATURE_IDNUMBER
 * @uses FEATURE_GROUPS
 * @uses FEATURE_GROUPINGS
 * @uses FEATURE_GROUPMEMBERSONLY
 * @uses FEATURE_MOD_INTRO
 * @uses FEATURE_COMPLETION_TRACKS_VIEWS
 * @uses FEATURE_GRADE_HAS_GRADE
 * @uses FEATURE_GRADE_OUTCOMES
 * @param string $feature FEATURE_xx constant for requested feature
 * @return bool|null True if module supports feature, false if not, null if doesn't know
 */
function olw_supports($feature) {
    switch($feature) {
        case FEATURE_IDNUMBER:                return false;
        case FEATURE_GROUPS:                  return false;
        case FEATURE_GROUPINGS:               return false;
        case FEATURE_GROUPMEMBERSONLY:        return true;
        case FEATURE_MOD_INTRO:               return true;
        case FEATURE_COMPLETION_TRACKS_VIEWS: return false;
        case FEATURE_GRADE_HAS_GRADE:         return false;
        case FEATURE_GRADE_OUTCOMES:          return false;
        case FEATURE_MOD_ARCHETYPE:           return MOD_ARCHETYPE_RESOURCE;
        case FEATURE_BACKUP_MOODLE2:          return true;
        case FEATURE_NO_VIEW_LINK:            return true;

        default: return null;
    }
}

/**
 * Register the ability to handle drag and drop file uploads
 * @return array containing details of the files / types the mod can handle
 */
function olw_dndupload_register() {
    $strdnd = get_string('dnduploadolw', 'mod_olw');
    if (get_config('olw', 'dndmedia')) {
        $mediaextensions = file_get_typegroup('extension', 'web_image');
        $files = array();
        foreach ($mediaextensions as $extn) {
            $extn = trim($extn, '.');
            $files[] = array('extension' => $extn, 'message' => $strdnd);
        }
        $ret = array('files' => $files);
    } else {
        $ret = array();
    }

    $strdndtext = get_string('dnduploadolwtext', 'mod_olw');
    return array_merge($ret, array('types' => array(
        array('identifier' => 'text/html', 'message' => $strdndtext, 'noname' => true),
        array('identifier' => 'text', 'message' => $strdndtext, 'noname' => true)
    )));
}

/**
 * Handle a file that has been uploaded
 * @param object $uploadinfo details of the file / content that has been uploaded
 * @return int instance id of the newly created mod
 */
function olw_dndupload_handle($uploadinfo) {
    global $USER;

    // Gather the required info.
    $data = new stdClass();
    $data->course = $uploadinfo->course->id;
    $data->name = $uploadinfo->displayname;
    $data->intro = '';
    $data->introformat = FORMAT_HTML;
    $data->coursemodule = $uploadinfo->coursemodule;

    // Extract the first (and only) file from the file area and add it to the olw as an img tag.
    if (!empty($uploadinfo->draftitemid)) {
        $fs = get_file_storage();
        $draftcontext = context_user::instance($USER->id);
        $context = context_module::instance($uploadinfo->coursemodule);
        $files = $fs->get_area_files($draftcontext->id, 'user', 'draft', $uploadinfo->draftitemid, '', false);
        if ($file = reset($files)) {
            if (file_mimetype_in_typegroup($file->get_mimetype(), 'web_image')) {
                // It is an image - resize it, if too big, then insert the img tag.
                $config = get_config('olw');
                $data->intro = olw_generate_resized_image($file, $config->dndresizewidth, $config->dndresizeheight);
            } else {
                // We aren't supposed to be supporting non-image types here, but fallback to adding a link, just in case.
                $url = moodle_url::make_draftfile_url($file->get_itemid(), $file->get_filepath(), $file->get_filename());
                $data->intro = html_writer::link($url, $file->get_filename());
            }
            $data->intro = file_save_draft_area_files($uploadinfo->draftitemid, $context->id, 'mod_olw', 'intro', 0,
                                                      null, $data->intro);
        }
    } else if (!empty($uploadinfo->content)) {
        $data->intro = $uploadinfo->content;
        if ($uploadinfo->type != 'text/html') {
            $data->introformat = FORMAT_PLAIN;
        }
    }

    return olw_add_instance($data, null);
}

/**
 * Resize the image, if required, then generate an img tag and, if required, a link to the full-size image
 * @param stored_file $file the image file to process
 * @param int $maxwidth the maximum width allowed for the image
 * @param int $maxheight the maximum height allowed for the image
 * @return string HTML fragment to add to the olw
 */
function olw_generate_resized_image(stored_file $file, $maxwidth, $maxheight) {
    global $CFG;

    $fullurl = moodle_url::make_draftfile_url($file->get_itemid(), $file->get_filepath(), $file->get_filename());
    $link = null;
    $attrib = array('alt' => $file->get_filename(), 'src' => $fullurl);

    if ($imginfo = $file->get_imageinfo()) {
        // Work out the new width / height, bounded by maxwidth / maxheight
        $width = $imginfo['width'];
        $height = $imginfo['height'];
        if (!empty($maxwidth) && $width > $maxwidth) {
            $height *= (float)$maxwidth / $width;
            $width = $maxwidth;
        }
        if (!empty($maxheight) && $height > $maxheight) {
            $width *= (float)$maxheight / $height;
            $height = $maxheight;
        }

        $attrib['width'] = $width;
        $attrib['height'] = $height;

        // If the size has changed and the image is of a suitable mime type, generate a smaller version
        if ($width != $imginfo['width']) {
            $mimetype = $file->get_mimetype();
            if ($mimetype === 'image/gif' or $mimetype === 'image/jpeg' or $mimetype === 'image/png') {
                require_once($CFG->libdir.'/gdlib.php');
                $tmproot = make_temp_directory('mod_olw');
                $tmpfilepath = $tmproot.'/'.$file->get_contenthash();
                $file->copy_content_to($tmpfilepath);
                $data = generate_image_thumbnail($tmpfilepath, $width, $height);
                unlink($tmpfilepath);

                if (!empty($data)) {
                    $fs = get_file_storage();
                    $record = array(
                        'contextid' => $file->get_contextid(),
                        'component' => $file->get_component(),
                        'filearea'  => $file->get_filearea(),
                        'itemid'    => $file->get_itemid(),
                        'filepath'  => '/',
                        'filename'  => 's_'.$file->get_filename(),
                    );
                    $smallfile = $fs->create_file_from_string($record, $data);

                    // Replace the image 'src' with the resized file and link to the original
                    $attrib['src'] = moodle_url::make_draftfile_url($smallfile->get_itemid(), $smallfile->get_filepath(),
                                                                    $smallfile->get_filename());
                    $link = $fullurl;
                }
            }
        }

    } else {
        // Assume this is an image type that get_imageinfo cannot handle (e.g. SVG)
        $attrib['width'] = $maxwidth;
    }

    $img = html_writer::empty_tag('img', $attrib);
    if ($link) {
        return html_writer::link($link, $img);
    } else {
        return $img;
    }
}
