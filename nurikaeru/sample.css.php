<?php
//require���邾���Ŏg����
require('./nurikaeru.php');


//php�̕ϐ��g����
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
//php�Ń��[�v�ł���
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

