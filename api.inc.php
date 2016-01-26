<?php
/* -------------------------------------------------------------------------- *\
|* -[ Module-Diary - API ]------------------------------------------------ *|
\* -------------------------------------------------------------------------- */

/**
 * Training object
 *
 * @param mixed $training training id or object
 * @return object training object
 */
function api_moduleDiary_training($training){
 // get object
 if(is_numeric($training)){$training=$GLOBALS['db']->queryUniqueObject("SELECT * FROM `module-diary_trainings` WHERE `id`='".$training."'");}
 if(!$training->id){return FALSE;}
 // check and convert
 $training->sportText=api_moduleDiary_sportText($training);
 $training->sortText=api_moduleDiary_sortText($training);
 $training->description=stripslashes($training->description);
 return $training;
}

/**
 * Sport text
 *
 * @param mixed $training training object
 * @return string sport text
 */
function api_moduleDiary_sportText($training){
 switch($training->sport){
  case "R":$return=api_text("sport-run");break;
  case "S":$return=api_text("sport-swim");break;
  case "B":$return=api_text("sport-bike");break;
  case "T":$return=api_text("sport-trail");break;
  case "W":$return=api_text("sport-snowshoes");break;
  case "N":$return=api_text("sport-rest");break;

  default:$return="[Sport not found]";
 }
 return $return;
}

/**
 * Ssort text
 *
 * @param mixed $training training object
 * @return string sort text
 */
function api_moduleDiary_sortText($training){
 switch($training->sort){
  case "S":$return=api_text("sort-slow");break;
  case "L":$return=api_text("sort-long");break;
  case "F":$return=api_text("sort-fast");break;
  case "N":$return=api_text("sort-rest");break;
  default:$return="[Sort not found]";
 }
 return $return;
}


/**
 * Trainings
 *
 * @param string $search search query
 * @param boolean $pagination limit query by page
 * @param string $where additional conditions
 * @return object $results array of address objects, $pagination pagination object, $query executed query
 */
function api_moduleDiary_trainings($search=NULL,$pagination=FALSE,$where=NULL){
 // definitions
 $return=new stdClass();
 $return->results=array();
 // generate query
 $query_table="`module-diary_trainings`";
 // fields
 $query_fields="*";
 // where
 $query_where=$GLOBALS['navigation']->filtersQuery(1);
 // search
 if(strlen($search)>0){
  $query_where.=" AND ";
  $query_where.="( `sport` LIKE '%".$search."%'";
  $query_where.=" OR `sort` LIKE '%".$search."%' )";
 }
 // conditions
 if(strlen($where)>0){$query_where="( ".$query_where." ) AND ( ".$where." )";}
 // order
 $query_order=api_queryOrder("`datetraining` DESC");
 // pagination
 if($pagination){
  $return->pagination=new str_pagination($query_table,$query_where,$GLOBALS['navigation']->filtersGet());
  // limit
  $query_limit=$return->pagination->queryLimit();
 }
 // build query
 $return->query="SELECT ".$query_fields." FROM ".$query_table." WHERE ".$query_where.$query_order.$query_limit;
 // execute query
 $results=$GLOBALS['db']->query($return->query);
 while($result=$GLOBALS['db']->fetchNextObject($results)){$return->results[$result->id]=api_moduleDiary_training($result);}
 // return objects
 return $return;
}

/**
 * Trainings export
 *
 * @return object $results array
 */
function api_moduleDiary_trainings_export (){
 // definitions
 $return=new stdClass();
 $return->results=array();
 // generate query
 $query_table="`module-diary_trainings`";
 // fields
 $query_fields="*";
 // build query
 $return->query= "SELECT ".$query_fields." FROM ".$query_table;
 // execute query
 $results=$GLOBALS['db']->query($return->query);
 while($result=$GLOBALS['db']->fetchNextObject($results)){$return->results[$result->id]=api_moduleDiary_training($result);
 // return objects
 return $return;
}}
?>