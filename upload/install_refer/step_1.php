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

		$this->core->title = $this->lng_m['mod_name'].' — '.$this->lng_m['step_1'];

		$bc = array(
			$this->lng_m['mod_name'] => BASE_URL."install_refer/",
			$this->lng_m['step_1'] => BASE_URL."install_refer/?mode=step_1"
		);

		$this->core->bc = $this->core->gen_bc($bc);
	}

	public function content(){
		if(isset($_SESSION['step_1'])){ $this->core->notify('', '', 4, '?mode=step_2'); }

		if($_SERVER['REQUEST_METHOD']=='POST'){

			if(!is_writable(MCR_ROOT.'configs/modules/refer.php') || !is_readable(MCR_ROOT.'configs/modules/refer.php')){ $this->core->notify($this->lng['e_msg'], $this->lng_m['e_perm_config'], 2, '?mode=step_1'); }

			$_SESSION['step_1'] = true;

			$this->core->notify($this->lng_m['mod_name'], $this->lng_m['step_2'], 4, '?mode=step_2');

		}

		$data = array(

			"CONFIG" => (is_writable(MCR_ROOT.'configs/modules/refer.php') && is_readable(MCR_ROOT.'configs/modules/refer.php')) ? '<b class="text-success">'.$this->lng['yes'].'</b>' : '<b class="text-error">'.$this->lng['no'].'</b>',
		);

		return $this->core->sp(MCR_ROOT."install_refer/theme/step_1.html", $data);
	}

}

?>