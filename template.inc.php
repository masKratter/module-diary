<?php
/* -------------------------------------------------------------------------- *\
|* -[ Module-Diary - Template ]------------------------------------------- *|
\* -------------------------------------------------------------------------- */
// include module information file
include("module.inc.php");
// include core api functions
include("../core/api.inc.php");
// load module api and language
api_loadModule();
// print header
$html->header(api_text("module-title"),$module_name);
 // get objects
$training=api_moduleDiary_training($_GET['idTraining']);
// build navigation menu
global $navigation;
$navigation=new str_navigation((api_baseName()=="module-diary_list.php"?TRUE:FALSE));
// filters
if(api_baseName()=="module-diary_list.php"){
 // sport
 $navigation->addFilter("multiselect","sport",api_text("filter-sport"),array("R"=>api_text("filter-run"),"S"=>api_text("filter-swim"),"B"=>api_text("filter-bike"),"T"=>api_text("filter-trail"),"W"=>api_text("filter-snowshoes")));
 $navigation->addFilter("multiselect","sort",api_text("filter-sort"),array("S"=>api_text("filter-slow"),"L"=>api_text("filter-long"),"F"=>api_text("filter-fast")));
 // if not filtered load default filters
 if($_GET['resetFilters']||($_GET['filtered']<>1 && $_SESSION['filters'][api_baseName()]['filtered']<>1)){
  //include("filters.inc.php");
 }
}
// list
$navigation->addTab(api_text("module-diary-nav-list"),"module-diary_list.php");
if(api_baseName()=="module-diary_list.php"){
 $navigation->addSubTab(api_text("module-diary-nav-export_list"),"module-diary_export_list.php");
}
// operations
if($training->id){
 $navigation->addTab(api_text("module-diary-nav-operations"),NULL,NULL,"active");
 $navigation->addSubTab(api_text("module-diary-nav-edit"),"module-diary_edit.php?idTraining=".$training->id,NULL,NULL,(api_checkPermission($module_name,"training_edit")?TRUE:FALSE));
 $navigation->addSubTab(api_text("module-diary-nav-delete"),"submit.php?act=training_delete&idTraining=".$training->id,NULL,NULL,(api_checkPermission($module_name,"training_del")?TRUE:FALSE),"_self",api_text("module-diary-nav-delete-confirm"));
 $navigation->addSubTab(api_text("module-diary-nav-export"),"module-diary_export.php?idTraining=".$training->id);
}else{
 // add new, with check permission
 $navigation->addTab(api_text("module-diary-nav-add"),"module-diary_edit.php",NULL,NULL,(api_checkPermission($module_name,"training_\edit")?TRUE:FALSE));
}
// show navigation menu
$navigation->render();
// check permissions before displaying module
if($checkPermission==NULL){content();}else{if(api_checkPermission($module_name,$checkPermission,TRUE)){content();}}
// print footer
$html->footer();
?>