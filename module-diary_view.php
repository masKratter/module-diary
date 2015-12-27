<?php
/* -------------------------------------------------------------------------- *\
|* -[ Module-Diary - View ]----------------------------------------------- *|
\* -------------------------------------------------------------------------- */
$checkPermission="training_view";
include("template.inc.php");
function content(){
 // get objects
 $training=api_moduleDiary_training($_GET['idTraining']);
 if(!$training->id){echo api_text("trainingNotFound");return FALSE;}
 // build training dynamic list
 $training_dl=new str_dl("br","dl-horizontal");
 $training_dl->addElement(api_text("module-diary_view-dt-sport"),$training->sportText);
 $training_dl->addElement(api_text("module-diary_view-dt-sort"),$training->sortText);
 $training_dl->addElement(api_text("module-diary_view-dt-time"),$training->time);
 $training_dl->addElement(api_text("module-diary_view-dt-distance"),$training->distance);
 $training_dl->addElement(api_text("module-diary_view-dt-description"),$training->description);
 $training_dl->addElement(api_text("module-diary_view-dt-datetraining"),$training->datetraining);
 // show address dynamic list
 $training_dl->render();
 // debug
 if($_SESSION["account"]->debug){pre_var_dump($training,"print","training");}
}
?>