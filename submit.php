<?php
/* -------------------------------------------------------------------------- *\
|* -[ Module-Training - Submit ]--------------------------------------------- *|
\* -------------------------------------------------------------------------- */
// include core api functions
include("../core/api.inc.php");
// load module api and language
api_loadModule();
// get action
$act=$_GET['act'];
// switch actions
switch($act){
 // training
 case "training_save":training_save();break;
 case "training_delete":training_delete();break;
 // default
 default:
  $alert="?alert=submitFunctionNotFound&alert_class=alert-warning&act=".$act;
  header("location: index.php".$alert);
}


/**
 * Training Save
 */
function training_save(){
 // check training edit permission
 if(!api_checkPermission("module-diary","training_edit")){api_die("trainingDenied");}
 // get objects
 $training=api_moduleDiary_training($_GET['idTraining']);
 // acquire variables
 $p_sport=$_POST['sport'];
 $p_sort=$_POST['sort'];
 $p_time=$_POST['time'];
 $p_distance=$_POST['distance'];
 $p_description=addslashes($_POST['description']);
 $p_datetraining=$_POST['datetraining'];
 // build request query
 if($training->id){
  $query="UPDATE `module-diary_trainings` SET
   `sport`='".$p_sport."',
   `sort`='".$p_sort."',
   `time`='".$p_time."',
   `distance`='".$p_distance."',
   `description`='".$p_description."',
   `datetraining`='".$p_datetraining."',  
   `updDate`='".api_now()."',
   `updIdAccount`='".api_account()->id."'
   WHERE `id`='".$training->id."'";
  // execute query
  $GLOBALS['db']->execute($query);
  // log event
  $log=api_log(API_LOG_NOTICE,"module-diary","trainingUpdated",
   "{logs_module-diary_trainingUpdated|".$p_sport."|".$p_sort."}",
   $training->id,"module-diary/module-diary_view.php?idTraining=".$training->id);
  // alert
  $alert="&alert=trainingUpdated&alert_class=alert-success&idLog=".$log->id;
 }else{
  $query="INSERT INTO `module-diary_trainings`
   (`sport`,`sort`,`time`,`distance`,`description`,`datetraining`,`addDate`,`addIdAccount`) VALUES
   ('".$p_sport."','".$p_sort."','".$p_time."','".$p_distance."','".$p_description."','".$p_datetraining."',
    '".api_now()."','".api_account()->id."')";
  // execute query
  $GLOBALS['db']->execute($query);
  // build from last inserted id
  $training=api_moduleDiary_training($GLOBALS['db']->lastInsertedId());
  // log event
  $log=api_log(API_LOG_NOTICE,"module-diary","trainingCreated",
   "{logs_module-diary_trainingCreated|".$p_sport."|".$p_sort."}",
   $training->id,"casting-reassignments/requests_view.php?idRequest=".$training->id);
  // alert
  $alert="&alert=trainingCreated&alert_class=alert-success&idLog=".$log->id;
 }
 // redirect
 exit(header("location: module-diary_view.php?idTraining=".$training->id.$alert));
}

/**
 * Training Delete
 */
function training_delete(){
 // check training edit permission
 if(!api_checkPermission("module-diary","training_del")){api_die("trainingDenied");}
 // get objects
 $training=api_moduleDiary_training($_GET['idTraining']);
 if(!$training->id){exit(header("location: module-diary_list.php?alert=trainingNotFound&alert_class=alert-error"));}
 // execute queries
 $GLOBALS['db']->execute("DELETE FROM `module-diary_trainings` WHERE `id`='".$training->id."'");
 // log event
  $log=api_log(API_LOG_WARNING,"module-diary","trainingDeleted",
   "{logs_module-diary_trainingDeleted|".$training->sport."|".$training->sort."}",
   $training->id);
 // redirect
 $alert="?alert=trainingDeleted&alert_class=alert-warning&idLog=".$log->id;
 exit(header("location: module-diary_list.php".$alert));
}

?>