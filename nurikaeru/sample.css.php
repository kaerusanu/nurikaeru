<?php
//requireするだけで使える
require('./nurikaeru.php');


//phpの変数使える
$header_color = '#4169e1';
$title_color =  '#6495ed';
$border_color = $title_color;

?>

#body{
	line-height:1.5em;
	font-size:12px;
	background:#fafafa;
}	


.title{
	background:<?= $header_color ?>;
	padding:5px;
	width:100%;
	color:#FFFFFF;
}


<?
//phpでループできる
foreach (range(0, 10) as $dl) { ?>
.dl<?= $dl ?>_title {
	background:#<?= $mc = mergeColor($title_color,'ffffff',$dl*20)?>;
	border-bottom:1px solid <?= $mc ?>;
}

.hd_dl<?= $dl ?>_border {
	border:1px solid #<?= $mc ?>;
}

<?
}
?>

