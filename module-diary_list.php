<?php
/* -------------------------------------------------------------------------- *\
|* -[ Module-Diary - List ]----------------------------------------------- *|
\* -------------------------------------------------------------------------- */
// script permission
$checkPermission="training_view";
// include modul template
include("template.inc.php");
// script content
function content(){
 // acquire variables
 $g_search=$_GET['q'];
 // show filters
 echo $GLOBALS['navigation']->filtersText();
 // build table
 $trainings_table=new str_table(api_text("module-diary_list-tr-unvalued"),TRUE,$GLOBALS['navigation']->filtersGet());
 $trainings_table->addHeader("&nbsp;",NULL,"16");
 $trainings_table->addHeader(api_text("module-diary_list-th-sport"),"nowarp",NULL,"`module-diary_trainings`.`sport`");
 $trainings_table->addHeader(api_text("module-diary_list-th-sort"),"nowarp",NULL,"`module-diary_trainings`.`sort`");
 $trainings_table->addHeader(api_text("module-diary_list-th-time"),"nowarp",NULL,"`module-diary_trainings`.`time`");
 $trainings_table->addHeader(api_text("module-diary_list-th-distance"),"nowarp",NULL,"`module-diary_trainings`.`distance`");
 $trainings_table->addHeader(api_text("module-diary_list-th-datetraining"),"nowarp",NULL,"`module-diary_trainings`.`datetraining`");
 $trainings_table->addHeader(api_text("module-diary_list-th-description"),NULL,"40%");
 // get trainings
 $trainings=api_moduleDiary_trainings($g_search,TRUE);
 foreach($trainings->results as $training){
  // check selected
  if($training->id==$_GET['idTraining']){$tr_class="info";}else{$tr_class=NULL;}
  // build address table row
  $trainings_table->addRow($tr_class);
  // build table fields
  $trainings_table->addField(api_link("module-diary_view.php?idTraining=".$training->id,api_icon("icon-search",api_text("module-diary_list-td-view"))),"nowarp");
  $trainings_table->addField($training->sportText,"nowarp");
  $trainings_table->addField($training->sortText,"nowarp");;
  $trainings_table->addField($training->time,"nowarp");
  $trainings_table->addField($training->distance,"nowarp");
  
  
  /**
* Timestamp Format
* @param string $timestamp MySql datetime
* @param string $format datetime format ( php date format or language key )
* @param string $language language conversion
* @return string formatted date time
*/
//function api_timestampFormat($timestamp,$format="Y-m-d H:i:s",$language=NULL){
  //$trainings_table->addField(api_timestampFormat($training->datetraining,"D-d-M-y"));  
  
  
  
  
  
  $training->datetraining = strtotime($training->datetraining); 
  $giorno = date('D-d-M-y',$training->datetraining);
  $trainings_table->addField($giorno);
  //$trainings_table->addField($training->datetraining);
  $trainings_table->addField($training->description,"nowarp");
 }
 // show table
 $trainings_table->render();
 // renderize the pagination
 $trainings->pagination->render();
 // debug
 if($_SESSION["account"]->debug){
  pre_var_dump($trainings->query,"print","query");
  pre_var_dump($trainings->results,"print","trainings");
 }
}
?>