<?php
$filenum = 3;
ini_set("display_errors", true);
error_reporting(E_ALL);
set_time_limit(0);

$notes = "";

$input = [ 'A', 'B', "C", "D", "E", "F", "G", "a", "b", "c", "NU"];
$t = rand(10, 30);
$nts = array();
for ($i = 0; $i < $t; $i++) {
    $nts[] = $input[array_rand($input)];
}
$notes = implode(" ", $nts);

$filenum = str_replace(" ", "-", $notes);

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
$bpm = 8;
$vol = 0.08;
$AA = new sound(199, $vol);
$BB = new sound(175, $vol);
$CC = new sound(165, $vol);
$DD = new sound(149, $vol);
$EE = new sound(132, $vol);
$FF = new sound(125, $vol);
$GG = new sound(112, $vol);
$A = new sound(100, $vol);
$B = new sound(90, $vol);
$C = new sound(85, $vol);
$D = new sound(75, $vol);
$E = new sound(67, $vol);
$F = new sound(62, $vol);
$G = new sound(55, $vol);
$a = new sound(49, $vol);
$b = new sound(44, $vol);
$c = new sound(42, $vol);

$NU = new sound(10000000, $vol);
$nu = new sound(10000000, $vol);

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
$d = 0;
if ($handle2) {
    fputs($handle2, "; Sample Rate 44100\n");
    fputs($handle2, "; Channels 1\n");

    $bps = ($bpm) / 60;
    $freq = 0;
    for ($seconds = 0; $seconds < (count($list_of_notes) - 1) * $bpm / 60; $seconds+=2e-5) {
        $d++;
        if ($freq >= 1 || $freq <= -1)
            $freq = 0;
        $tempo = (intval($seconds / $bps));
        if (isset($list_of_notes[$tempo])) {
            if ($tempo != $prev) {
                $tft = explode("|", $list_of_notes[$prev]);
                foreach ($tft as $e) {
                    $$e->pause = true;
                }
                $prev = $tempo;
            }
            $tft = explode("|", $list_of_notes[$tempo]);
            foreach ($tft as $tb) {
                if (!isset($$tb)) {
                    echo "failed for $tb";
                }
                $$tb->pause = false;
                $$tb->is_on($d, $freq);
            }
        } else {
            echo $tempo;
            exit();
        }
        fputs($handle2, "$seconds $freq\n");
    }
    fclose($handle2);
} else {
    echo "failed open file";
}
exec("sox $filenum.dat output/$filenum.ogg");
@unlink("$filenum.dat");

class sound {

    function __construct($high, $vol) {
        $this->high = $high;
        $this->vol = $vol;
    }

    public $high = 100;
    public $vol = 0.02;
    public $is_on = 1;
    public $pause = false;

    public function pause() {
        if ($this->pause == false) {
            $this->pause = true;
        } else {
            $this->pause = false;
        }
    }

    public function down(&$freq) {
        $freq-=$this->vol;
    }

    public function is_on($delta, &$freq = 0) {
        if (!$this->pause) {
            $e = intval($delta / $this->high) % 2;
            if ($this->is_on != $e) {
                if ($this->is_on == 1) {
                    $freq+=$this->vol;
                } else {
                    $freq-=$this->vol;
                }
                $this->is_on = $e;
            }
        }
        return $freq;
    }

}

echo $notes;
?>

<audio controls autoplay>
    <source src="output/<?= $filenum; ?>.ogg" type="audio/ogg">

</audio>
