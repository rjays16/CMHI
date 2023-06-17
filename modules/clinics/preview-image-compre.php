<?php

require('./roots.php');
require($root_path.'include/inc_environment_global.php');

$img_path = $root_path.compre_img;

define('NO_2LEVEL_CHK',1);
$local_user='ck_pflege_user';

require_once($root_path.'include/inc_front_chain_lang.php');
require_once($root_path.'gui/smarty_template/smarty_care.class.php');
require_once($root_path.'include/care_api_classes/class_compre_discharge.php');

$encounter_nr = $_GET['encounter_nr'];
$filenameIndex = $_GET['index'];

$compre_obj = new Compre_discharge();
$compre_obj->details = array("encounter_nr"=>$encounter_nr); 
$images = $compre_obj->getImages();

while($row = $images->FetchRow()){
	$filenames .= $row['filename'].",";
}

?>

<link rel="stylesheet" href="<?= $root_path ?>js/jquery/themes/seg-ui/jquery.ui.all.css" type="text/css" />
<script type="text/javascript" src="<?=$root_path?>js/jquery/jquery.js"></script>
<script type="text/javascript" src="<?=$root_path?>js/jquery/ui/jquery-ui.js"></script>
<script type="text/javascript" src="<?=$root_path?>js/jquery/jquery-1.8.2.js"></script>
<style>

#background{
	height: 100%;
	width: 100%;
	background: #000000;	
}

#large img {
	border: none;
	width: 100%;
    height: 100%;
}

#large {
	max-height: 500px;
	max-width: 500px;	
	margin: auto;
   	position: absolute;
	background: white;
	z-index: 1;
}

#next {
    position:absolute;
    right:0px;
    height: 100%;
    padding-top: 40%;
}

#previous {
	position:absolute;
    left:0px;
    height: 100%;
    padding-top: 40%;
}

.buttons img{
	cursor: pointer;
	opacity: .2;
}

.buttons:hover img{
	 opacity: 1;
}

</style>
<script>
	var filenames;
	var currIndex;

	jQuery.fn.center = function () {
		this.css("position","absolute");
		this.css("top", ( $(window).height() - this.height() ) / 2+$(window).scrollTop() + "px");
		this.css("left", ( $(window).width() - this.width() ) / 2+$(window).scrollLeft() + "px");
		return this;
	}

	$(document).ready(function(){
		var encounter_nr = $('#encounter_nr').val();
		currIndex = $('#filename_index').val();
		filenames = $("#filenames").val().split(",");

		var images = new Array(); 
		preview();
	});

	function preview(){
		var div = $("#large");
		var img_path = $("#img_path").val();
		var img_src = img_path+filenames[currIndex];

		div.html("<a href='"+img_src+"'><img src='"+img_src+"'/></a>").center();	
		buttons();
	}

	function setIndex(action){
		switch(action){
			case 'next':
				currIndex++;
			break;

			case 'previous':
				currIndex--;
			break;
		}

		preview();
	}

	function buttons(){
		$("#previous").show();
		$("#next").show();

		if(currIndex <= 0 && filenames.length <= 2){
			$("#previous").hide();
			$("#next").hide();
		}else if(currIndex == 0 ){
			$("#previous").hide();
		}else if(currIndex == (filenames.length-2)){
			$("#next").hide();
		}
	}

</script>
<body id="background">
<div id="center">

	<input type="hidden" id="encounter_nr" name="encounter_nr" value="<?=$encounter_nr?>"/>
	<input type="hidden" id="filename_index" name="filename_index" value="<?=$filenameIndex?>"/>
	<input type="hidden" id="filenames" name="filenames" value="<?=$filenames?>">
	<input type="hidden" id="img_path" name="img_path" value="<?=$img_path?>">
	
	<div id="previous" class="buttons"><img title="previous" onclick="setIndex('previous'); return false;" src="<?=$root_path?>images/previous.png"></div>
	<div id="large"></div> 
	<div id="next" class="buttons"><img title="next" onclick="setIndex('next'); return false;" src="<?=$root_path?>images/next.png"></div>

</div>
</body>