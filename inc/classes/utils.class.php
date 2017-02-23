<?php

class Utils {
	/**
	 *  Affiche un tableau de façon claire (debug)
	 **/
	 public static function println($str) {
		echo $str . "<br/>";
	}
	/**
	 * Affiche un tableau de façon claire (debug)
	 **/
	 public static function print_tab($tab) {
		echo "<pre>";
		print_r($tab);
		echo "</pre>";
	}
    
	/**
	 * Affiche une notification
	 **/
	 public static function notification($msg, $type = "success") {
		$_SESSION["notification"] = array("msg" => $msg, "type" => $type);
	}

	/**
	 * @desc récupère une variable d'un POST ou GET (post, get, both)
	 */
	 public static function get_input($name = "", $type = "") {
		//global $_POST, $_GET;
	
		$tdis = "";
		$magic = get_magic_quotes_gpc();
	
		if (($type == "get") || ($type == "both")) {
			if (isset($_GET["$name"])) {
				$tdis = $_GET["$name"];
				if ($magic && !is_array($tdis)) {
					$tdis = trim(stripslashes($tdis));
				}
			}
		}
		if (($type == "post") || ($type == "both")) {
			if (isset($_POST["$name"])) {
				$tdis = $_POST["$name"];
				if ($magic && !is_array($tdis)) {
					$tdis = trim(stripslashes($tdis));
				}
			}
		}
		return $tdis;
	}
	
	/**
	 * @desc 	Redirige le navigateur vers la page $page.
	 **/
	 public static function redirection($page) {
		header("Location: " . $page);
		exit();
	}

}
?>