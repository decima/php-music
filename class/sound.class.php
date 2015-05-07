<?php

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
