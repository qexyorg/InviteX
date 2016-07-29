INSERT INTO `mcr_permissions` (`title`, `description`, `value`, `system`, `type`, `default`, `data`) VALUES
('Пункт меню "InviteX"', 'Доступ к пункту меню "InviteX" в панели управления.', 'mod_adm_m_i_refer', 0, 'boolean', 'false', '{"time_create":1005553535,"time_last":1005553535,"login_create":"admin","login_last":"admin"}'),
('Доступ к рефералам', 'Дает доступ к реферальной системе', 'mod_refer_list', 0, 'boolean', 'false', '{"time_create":1005553535,"time_last":1005553535,"login_create":"admin","login_last":"admin"}'),
('Доступ к настройкам InviteX', 'Дает доступ к панели управления реферальной системе', 'mod_refer_adm_settings', 0, 'boolean', 'false', '{"time_create":1005553535,"time_last":1005553535,"login_create":"admin","login_last":"admin"}');
#line
CREATE TABLE IF NOT EXISTS `mod_users_comments` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `ip` varchar(16) NOT NULL DEFAULT '127.0.0.1',
  `uid` int(10) NOT NULL DEFAULT '0',
  `date` int(10) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
#line