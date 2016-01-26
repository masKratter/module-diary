<?php
/* -------------------------------------------------------------------------- *\
|* -[ Module-Diary - Edit ]----------------------------------------------- *|
\* -------------------------------------------------------------------------- */
$checkPermission="training_edit";
include("template.inc.php");
function content(){
 // get object
 $training=api_moduleDiary_training($_GET['idTraining']);
 //build training form
 $training_form=new str_form("submit.php?act=training_save&idTraining=".$training->id,"post","training_edit");
 $training_form->addField("radio","sport",api_text("module-diary_edit-ff-sport"),NULL,"inline");
 $training_form->addFieldOption("R",api_text("module-diary_edit-fo-sport-run"),(!$training->id||"R"==$training->sport?TRUE:FALSE));
 $training_form->addFieldOption("S",api_text("module-diary_edit-fo-sport-swim"),("S"==$training->sport?TRUE:FALSE));
 $training_form->addFieldOption("B",api_text("module-diary_edit-fo-sport-bike"),("B"==$training->sport?TRUE:FALSE));
 $training_form->addFieldOption("T",api_text("module-diary_edit-fo-sport-trail"),("T"==$training->sport?TRUE:FALSE));
 $training_form->addFieldOption("W",api_text("module-diary_edit-fo-sport-snowshoes"),("W"==$training->sport?TRUE:FALSE));
 $training_form->addFieldOption("N",api_text("module-diary_edit-fo-sport-rest"),("N"==$training->sport?TRUE:FALSE));
 $training_form->addField("radio","sort",api_text("module-diary_edit-ff-sort"),NULL,"inline");
 $training_form->addFieldOption("S",api_text("module-diary_edit-fo-sort-slow"),(!$training->id||"S"==$training->sort?TRUE:FALSE));
 $training_form->addFieldOption("L",api_text("module-diary_edit-fo-sort-long"),("L"==$training->sport?TRUE:FALSE));
 $training_form->addFieldOption("F",api_text("module-diary_edit-fo-sort-fast"),("F"==$training->sport?TRUE:FALSE));
 $training_form->addFieldOption("N",api_text("module-diary_edit-fo-sort-rest"),("N"==$training->sport?TRUE:FALSE));
 $training_form->addField("text","time",api_text("module-diary_edit-ff-time"),$training->time,"input-medium",api_text("module-diary_edit-ff-time-placeholder"));
 $training_form->addField("text","distance",api_text("module-diary_edit-ff-distance"),$training->distance,"input-small",api_text("module-diary_edit-ff-distance-placeholder"));
 $training_form->addField("text","description",api_text("module-diary_edit-ff-description"),$training->description,"input-large",api_text("module-diary_edit-ff-description-placeholder"));
 $training_form->addField("date","datetraining",api_text("module-diary_edit-ff-datetraining"),$training->datetraining,"input-small");
 
// controls
 $training_form->addControl("submit",api_text("module-diary_edit-fc-submit"));
 if($training->id){$training_form->addControl("button",api_text("module-diary_edit-fc-cancel"),NULL,"module-training_view.php?idTraining=".$training->id);}
 else{$training_form->addControl("button",api_text("module-diary_edit-fc-cancel"),NULL,"module-training_list.php");}
 // show training form
 $training_form->render();
 // debug
 if($_SESSION["account"]->debug){pre_var_dump($training,"print","training");}
?>
<script type="text/javascript">
 $(document).ready(function(){
  // validation
  $("form[name='training_edit']").validate({
   rules:{
    sport:{required:true,minlength:1},
    sort:{required:true,minlength:1}
   },
   submitHandler:function(form){form.submit();}
  });
 });
</script>
<?php } ?>