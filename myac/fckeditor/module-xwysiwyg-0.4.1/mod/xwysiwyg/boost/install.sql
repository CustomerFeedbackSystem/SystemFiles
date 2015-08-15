CREATE TABLE mod_xw_editor (
	editor varchar(20) NOT NULL default ''
	);

CREATE TABLE mod_xw_config (
	editor varchar(20) NOT NULL default '',
	path varchar(127) NOT NULL default '',
	browsers varchar(70) NOT NULL default 'FB.6+;NS7+;IE5.5+;MZ1.4+;FX.10;FX1+',
	height varchar(6) NOT NULL default 'auto',
	width varchar(6) NOT NULL default 'auto',
	enable_css smallint(1) NOT NULL default '0',
	lang_activ smallint(1) NOT NULL default '0',
	view_anon smallint(1) NOT NULL default '0',
	view_user smallint(1) NOT NULL default '1',
	request_mode smallint(1) NOT NULL default '0',
	plugins text NOT NULL,
	themes text NOT NULL,
	theme varchar(25) NOT NULL default 'none'
	);

CREATE TABLE mod_xw_areas (
	id int NOT NULL default '0',
	area varchar(50) NOT NULL default '',
	PRIMARY KEY  (id)
);
