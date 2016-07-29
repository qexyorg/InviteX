<?php

if(!defined("MCR")){ exit("Hacking Attempt!"); }

class submodule{
	private $core, $db, $cfg, $user, $lng, $cfg_m;

	public function __construct($core){
		$this->core		= $core;
		$this->db		= $core->db;
		$this->cfg		= $core->cfg;
		$this->user		= $core->user;
		$core->lng_m	= $core->load_language('refer');
		$this->lng		= $core->lng_m;

		require_once(MCR_CONF_PATH.'modules/refer.php');

		$this->cfg_m	= $cfg;

		if(!$this->core->is_access('mod_refer_adm_settings')){ $this->core->notify($this->core->lng['403'], $this->core->lng['e_403']); }

		$bc = array(
			$this->lng['mod_name'] => BASE_URL."?mode=admin",
			$core->lng_m['mod_name_cp'] => BASE_URL."?mode=admin&do=refer"
		);

		$this->core->bc = $this->core->gen_bc($bc);
	}

	public function content(){

		$cfg = $this->cfg_m;

		if($_SERVER['REQUEST_METHOD']=='POST'){

			$cfg['MOD_ROP']			= (intval(@$_POST['rop']) <= 0) ? 1 : intval(@$_POST['rop']);

			$cfg['MOD_PRIZE']		= floatval(@$_POST['prize']);

			$cfg['MOD_PRIZE_TYPE']	= (intval(@$_POST['type']) == 1) ? 1 : 0;

			if(!$this->cfg->savecfg($cfg, 'modules/refer.php', 'cfg')){ $this->core->notify($this->core->lng["e_msg"], $this->core->lng_m['set_e_cfg_save'], 2, '?mode=admin&do=refer'); }

			// Последнее обновление пользователя
			$this->db->update_user($this->user);

			// Лог действия
			$this->db->actlog($this->core->lng_m['log_set_main_save'], $this->user->id);
			
			$this->core->notify($this->core->lng["e_success"], $this->core->lng_m['set_save_success'], 3, '?mode=admin&do=refer');
		}

		$data = array(
			'CFG'			=> $cfg,
			'TYPE'			=> ($cfg['MOD_PRIZE_TYPE']==1) ? 'selected' : '',
		);

		return $this->core->sp(MCR_THEME_MOD."admin/refer/main.html", $data);
	}
}

?>