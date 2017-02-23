<?php
/**
* @project		memo_info
* @author		Olivier Gaillard
* @version		1.0 du 16/02/2017
* @desc			Controleur des objets : memos
*/
require 'vendor/autoload.php';
require_once 'inc/prepend.php';
require_once 'inc/classes/utils.class.php';
require_once 'inc/classes/memo.class.php';

use Elasticsearch\ClientBuilder;
$client = ClientBuilder::create()->build();
$params = array();
$params['index'] = 'support';
$params['type']  = 'memo';


// Récupération des variables
$action			= Utils::get_input('action','both');
$page			= Utils::get_input('page','both');
$id				= Utils::get_input('id','both');
$title			= Utils::get_input('title','post');
$comment		= Utils::get_input('comment','post');
$tags			= Utils::get_input('tags','post');
//$doc_link		= Utils::get_input('doc_link','post');
$query			= Utils::get_input('query','post');

$smarty->assign("query", $query);

switch($action) {
	
	case "add" :
		$smarty->assign("infos", array("title" => "Ajouter un memo"));
		$smarty->assign("content", "memos/edit.tpl.html");
		$smarty->display("main.tpl.html");
		break;
	
	case "edit" :
		$smarty->assign("infos", array("title" => "Modifier un memo"));
		$params['id'] = $id;
        $result = $client->get($params);
        $smarty->assign("memo", $result);
        //$smarty->assign("memo", $memo_manager->getMemo($id));
		$smarty->assign("content","memos/edit.tpl.html");
		$smarty->display("main.tpl.html");
		break;

	case "search" :
		$smarty->assign("content","memos/search.tpl.html");
		$smarty->display("main.tpl.html");
		break;

	case "search_results" :
		 if ( strlen($query) > 2) {
            $json = '{
                "query" : {
                    "query_string" : {
                        "query" : "'.$query.'"
                    }
                }
            }'; 
			$params['body'] = $json;
            //$params = ['body' => ['query' => ['query_string' => ['query' => $query]]]];

            $results = $client->search($params);
            $smarty->assign("results", $results["hits"]);
            $total = $results["hits"]['total'];
		}
		else {
			Utils::notification("La requête saisie est trop courte !", "warning");
			Utils::redirection("index.php?action=search");
		}
		$smarty->assign("content","memos/search.tpl.html");
		$smarty->display("main.tpl.html");
		break;

	case "save" :
        if (strlen($id)) $params['id'] = $id;
        $params['body']  = array(
            'title' =>  $title,
            'comment' => $comment,
            'tags' => $tags,
            'lastUpdate' => date("Y-m-d H:M:S") 
        );
        $response = $client->index($params);
        Utils::notification("Le mémo a été enregistré");
		Utils::redirection("index.php");
		break;

	case "delete" :
        $params['id'] = $id;
        $response = $client->delete($params);
		Utils::notification("Le mémo a été effacé");
        Utils::redirection("index.php");
		break;

	default:
		$smarty->assign("content", "memos/search.tpl.html");
		$smarty->display("main.tpl.html");
}

?>