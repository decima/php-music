<?php
$filenum = 3;
ini_set("display_errors", true);
error_reporting(E_ALL);
set_time_limit(0);

require_once ('class/sound.class.php');

$notes = "";
$nts = array();

$notes = "D*4 A*4 B*4 FF_d*4 GG*4 DD*4 GG*4 A*4 NU "
        . "f_d|D*2 f_d|a*2 A|e*4 B|d*2 d|F_d*2 FF_b|c_d*4";
$filenum = str_replace(" ", "-", $notes);
$filenum = str_replace("|", ".", $filenum);
@unlink("$filenum.dat");
$handle2 = fopen("$filenum.dat", "a+");

/* $notes = $dMin;
  for ($i = 0; $i < 7; $i++) {
  $notes.=" $dMin";
  }
  for ($i = 0; $i < 8; $i++) {
  $notes.=" $aMin";
  } */
//$notes = "D|F|a*8 C|E|a*8 ";
//$notes="AA|A*4";
$bpm = 16;
$vol = 0.16;
$AA = new sound(199, $vol);
$AA_d = new sound(186, $vol);
$BB_b = new sound(184, $vol);
$BB = new sound(175, $vol);
$BB_d = new sound(165, $vol);
$CC_b = new sound(175, $vol);
$CC = new sound(165, $vol);
$CC_d = new sound(156, $vol);
$DD_b = new sound(154, $vol);
$DD = new sound(149, $vol);
$DD_d = new sound(141, $vol);
$EE_b = new sound(140, $vol);
$EE = new sound(132, $vol);
$EE_d = new sound(124, $vol);
$FF_b = new sound(131, $vol);
$FF = new sound(125, $vol);
$FF_d = new sound(119, $vol);
$GG_b = new sound(118, $vol);
$GG = new sound(112, $vol);
$GG_d = new sound(107, $vol);
$A_b = new sound(106, $vol);
$A = new sound(100, $vol);
$A_d = new sound(95, $vol);
$B_b = new sound(95, $vol);
$B = new sound(90, $vol);

$B_d = new sound(85, $vol);
$C_b = new sound(90, $vol);
$C = new sound(85, $vol);
$C_d = new sound(80, $vol);

$D_b = new sound(80, $vol);
$D = new sound(75, $vol);
$D_d = new sound(71, $vol);
$E_b = new sound(71, $vol);
$E = new sound(67, $vol);
$E_d = new sound(62, $vol);
$F_b = new sound(67, $vol);
$F = new sound(62, $vol);
$F_d = new sound(59, $vol);
$G_b = new sound(58, $vol);
$G = new sound(55, $vol);
$G_d = new sound(52, $vol);
$a_b = new sound(52, $vol);
$a = new sound(49, $vol);
$a_d = new sound(47, $vol);

$b_b = new sound(47, $vol);
$b = new sound(44, $vol);
$b_d = new sound(42, $vol);
$c_b = new sound(44, $vol);
$c = new sound(42, $vol);
$c_d = new sound(40, $vol);
$d_b = new sound(40, $vol);
$d = new sound(38, $vol);
$d_d = new sound(36, $vol);
$e_b = new sound(36, $vol);

$e = new sound(33, $vol);
$e_b = new sound(32, $vol);

$f_b = new sound(32, $vol);

$f = new sound(32, $vol);
$f_d = new sound(29.75, $vol);
$g_b = new sound(29.75, $vol);
$g = new sound(27.55, $vol);
$g_d = new sound(26.80, $vol);
$aa = new sound(26.80, $vol);
$aa = new sound(24.99, $vol);


$NU = $nu = new sound(10000000, $vol);

$list_of_notes_temp = explode(" ", $notes);
$list_of_notes = array();
foreach ($list_of_notes_temp as $l) {
    if (strpos($l, "*")) {
        $vals = explode("*", $l);
        for ($i = 0; $i < $vals[1]; $i++) {
            $list_of_notes[] = $vals[0];
        }
    } else {
        $list_of_notes[] = $l;
    }
}


$list_of_notes[-1] = "NU";


$prev = -1;
$counter = 0;
if ($handle2) {
    fputs($handle2, "; Sample Rate 44100\n");
    fputs($handle2, "; Channels 2\n");

    $bps = ($bpm) / 60;
    $freq = 0;
    for ($seconds = 0; $seconds < (count($list_of_notes) - 1) * $bpm / 60; $seconds+=2e-5) {
        $counter++;
        if ($freq >= 1 || $freq <= -1)
            $freq = 0;
        $tempo = (intval($seconds / $bps));
        if (isset($list_of_notes[$tempo])) {
            if ($tempo != $prev) {
                $tft = explode("|", $list_of_notes[$prev]);
                foreach ($tft as $et) {
                    $$et->pause = true;
                }
                $prev = $tempo;
            }
            $tft = explode("|", $list_of_notes[$tempo]);
            $new_vol = $vol;
            for ($i = 0; $i < count($tft); $i++) {
               // $new_vol /= 2;
            }
            foreach ($tft as $tb) {
                if (!isset($$tb)) {
                    echo "failed for $tb";
                }
                $$tb->pause = false;
                $$tb->vol = $new_vol;
                $$tb->is_on($counter, $freq);
            }
        } else {
            echo $tempo;
            exit();
        }
        fputs($handle2, "$seconds $freq $freq\n");
    }
    fclose($handle2);
} else {
    echo "failed open file";
}
exec("sox $filenum.dat output/$filenum.ogg");
unlink("$filenum.dat");

echo $notes;
?>

<audio controls autoplay>
    <source src="output/<?= $filenum; ?>.ogg" type="audio/ogg">
</audio>
