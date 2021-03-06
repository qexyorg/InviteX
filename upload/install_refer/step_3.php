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

		$this->core->title = $this->lng_m['mod_name'].' — '.$this->lng_m['step_3'];

		$bc = array(
			$this->lng_m['mod_name'] => BASE_URL."install_refer/",
			$this->lng_m['step_3'] => BASE_URL."?mode=step_3"
		);

		$this->core->bc = $this->core->gen_bc($bc);
	}

	public function content(){
		if(!isset($_SESSION['step_2'])){ $this->core->notify('', '', 4, '?mode=step_2'); }
		if(isset($_SESSION['step_3'])){ $this->core->notify('', '', 4, '?mode=finish'); }

		$ctables	= $this->core->cfg->db['tables'];
		$ug_f		= $ctables['ugroups']['fields'];

		$url = BASE_URL;

		if($_SERVER['REQUEST_METHOD']=='POST'){

			$tables = file(MCR_ROOT.'install_refer/tables.sql');

			$string = "";

			foreach($tables as $key => $value){

				$value = trim($value);

				if($value=='#line'){
					$string = trim($string);

					@$this->db->obj->query($string);

					$string = "";
					continue;
				}

				$string .= $value;

			}

			$sql1 = $this->db->query("INSERT INTO `mcr_menu`
										(`title`, `parent`, `url`, `target`, `permissions`)
									VALUES
										('Приглашения', 0, '$url?mode=refer', '_self', 'mod_refer_list')");

			if(!$sql1){ $this->core->notify($this->lng['e_msg'], $this->lng_m['e_add_menu'], 2, '?mode=step_3'); }
			
			$sql2 = $this->db->query("INSERT INTO `mcr_menu_adm_icons`
										(`title`, `img`)
									VALUES
										('InviteX', 'refer.png')");

			if(!$sql2){ $this->core->notify($this->lng['e_msg'], $this->lng_m['e_add_icon'], 2, '?mode=step_3'); }

			$iconid = $this->db->insert_id();

			$sql3 = $this->db->query("INSERT INTO `mcr_menu_adm`
										(`gid`, `title`, `text`, `url`, `target`, `access`, `priority`, `icon`)
									VALUES
										(5, 'InviteX', 'Управление InviteX', '$url?mode=admin&do=refer', '_self', 'mod_adm_m_i_refer', 5, '$iconid')");

			if(!$sql3){ $this->core->notify($this->lng['e_msg'], $this->lng_m['e_add_menu_adm'], 2, '?mode=step_3'); }

			$groups = array();

			$query = $this->db->query("SELECT `{$ug_f['id']}`, `{$ug_f['perm']}` FROM `{$this->core->cfg->tabname('ugroups')}`");

			if(!$query || $this->db->num_rows($query)<=0){ $this->core->notify($this->lng['e_msg'], $this->lng['e_msg'], 2, '?mode=step_3'); }

			while($ar = $this->db->fetch_assoc($query)){
				$groups[] = array(
					'id' => intval($ar[$ug_f['id']]),
					'permissions' => json_decode($ar[$ug_f['perm']], true),
				);
			}

			foreach($groups as $key => $value){
				$gid = intval($value['id']);

				$value['permissions']['mod_refer_list'] = ($gid==3 || $gid==2) ? true : false;
				$value['permissions']['mod_adm_m_i_refer'] = ($gid==3) ? true : false;
				$value['permissions']['mod_refer_adm_settings'] = ($gid==3) ? true : false;

				$newperm = json_encode($value['permissions']);

				$newperm = $this->db->safesql($newperm);

				$this->db->query("UPDATE `{$this->core->cfg->tabname('ugroups')}` SET `{$ug_f['perm']}`='$newperm' WHERE id='$gid'");
			}

			$this->core->cfg_m['MOD_INSTALL'] = false;

			if(!$this->cfg->savecfg($this->core->cfg_m, 'modules/refer.php', 'cfg')){
				$this->core->notify($this->lng['e_msg'], $this->lng_m['e_settings'], 2, '?mode=step_3');
			}

			$_SESSION['step_3'] = true;

			$this->core->notify($this->lng_m['mod_name'], $this->lng_m['finish'], 4, '?mode=finish');

		}

		return $this->core->sp(MCR_ROOT."install_refer/theme/step_3.html");
	}

}

?>