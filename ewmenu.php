<?php

// Menu
$RootMenu = new cMenu("RootMenu", TRUE);
$RootMenu->AddMenuItem(1, "mi_berita", $Language->MenuPhrase("1", "MenuText"), "beritalist.php", -1, "", TRUE, FALSE, FALSE, "fa-newspaper-o");
$RootMenu->AddMenuItem(2, "mi_fasilitas", $Language->MenuPhrase("2", "MenuText"), "fasilitaslist.php", -1, "", TRUE, FALSE, FALSE, "fa-briefcase");
$RootMenu->AddMenuItem(3, "mi_tanaman", $Language->MenuPhrase("3", "MenuText"), "tanamanlist.php", -1, "", TRUE, FALSE, FALSE, "fa-tree");
$RootMenu->AddMenuItem(8, "mci_Json_Berita", $Language->MenuPhrase("8", "MenuText"), "json_berita.php", -1, "", TRUE, FALSE, TRUE, "fa-newspaper-o");
$RootMenu->AddMenuItem(7, "mci_Json_Fasilitas", $Language->MenuPhrase("7", "MenuText"), "json_fasilitas.php", -1, "", TRUE, FALSE, TRUE, "fa-briefcase");
$RootMenu->AddMenuItem(6, "mci_Json_Tanaman", $Language->MenuPhrase("6", "MenuText"), "json_tanaman.php", -1, "", TRUE, FALSE, TRUE, "fa-tree");
echo $RootMenu->ToScript();
?>
<div class="ewVertical" id="ewMenu"></div>
