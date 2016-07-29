<?php

if(!defined("MCR")){ exit("Hacking Attempt!"); }

class module{
	private $core, $db, $cfg, $lng, $lng_m, $user;

	public function __construct($core){
		$this->core		= $core;
		$this->db		= $core->db;
		$this->user		= $core->user;
		$this->cfg		= $core->cfg;
		$this->lng		= $core->lng;
		$this->lng_m	= $core->lng_m;

		$this->core->title = $this->lng_m['mod_name'].' — '.$this->lng_m['step_2'];

		$bc = array(
			$this->lng_m['mod_name'] => BASE_URL."install_refer/",
			$this->lng_m['step_2'] => BASE_URL."install_refer/?mode=step_2"
		);

		$this->core->bc = $this->core->gen_bc($bc);
	}

	public function content(){
		if(!isset($_SESSION['step_1'])){ $this->core->notify('', '', 4, '?mode=step_1'); }
		if(isset($_SESSION['step_2'])){ $this->core->notify('', '', 4, '?mode=step_3'); }

		if($_SERVER['REQUEST_METHOD']=='POST'){

			$this->core->cfg_m['MOD_ROP']			= (intval(@$_POST['rop'])<=0) ? 1 : intval(@$_POST['rop']);
			$this->core->cfg_m['MOD_PRIZE']			= floatval(@$_POST['prize']);
			$this->core->cfg_m['MOD_PRIZE_TYPE']	= (intval(@$_POST['type'])==1) ? 1 : 0;

			if(!$this->cfg->savecfg($this->core->cfg_m, 'modules/refer.php', 'cfg')){
				$this->core->notify($this->lng['e_msg'], $this->lng_m['e_settings'], 2, '?mode=step_2');
			}

			$_SESSION['step_2'] = true;

			$this->core->notify($this->lng_m['mod_name'], $this->lng_m['step_2'], 4, '?mode=step_3');

		}

		$data = array(
			"TYPE" => ($this->core->cfg_m['MOD_PRIZE_TYPE']==1) ? 'selected' : '',
		);

		return $this->core->sp(MCR_ROOT."install_refer/theme/step_2.html", $data);
	}

}

?>