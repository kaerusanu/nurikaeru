nurikaeru
=========

lite css framework

phpで簡単にcssを拡張できます。

■変数展開、for、四則演算など(phpのまま)
<?= $hoge ?>
<?
foreach(array(1,2,3) as $k) {
?>
.hoge<?=$k?> {
  piko:value;
}
<?
}
?>

■クラス継承
クラスの属性を継承できます。
以下のように書きます。
.hoge @extends moge {
  attr:value;
}

.moge {
  attr:value2;
}

■使い方
requireするだけ。