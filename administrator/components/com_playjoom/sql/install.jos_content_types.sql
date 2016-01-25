INSERT INTO `#__content_types` (
	`type_id`, 
	`type_title`, 
	`type_alias`, 
	`table`, 
	`rules`, 
	`field_mappings`, 
	`router`, 
	`content_history_options`) 
VALUES (NULL, 
	'Track', 
	'com_playjoom.audiotrack', 
	'{"special":{"dbtable":"#__jpaudiotracks","key":"id","type":"audiotrack","prefix":"PlayJoomTable","config":"array()"},"common":{"dbtable":"#__core_content","key":"ucm_id","type":"Corecontent","prefix":"JTable","config":"array()"}}', '', '{"common":[{"core_content_item_id":"id","core_title":"title","core_state":"null","core_alias":"null","core_created_time":"add_datetime","core_modified_time":"mod_datetime","core_body":"null", "core_hits":"hits","core_publish_up":"null","core_publish_down":"null","core_access":"access", "core_params":"null", "core_featured":"null", "core_metadata":"null", "core_language":"null", "core_images":"null", "core_urls":"null", "core_version":"null", "core_ordering":"tracknumber", "core_metakey":"metakey", "core_metadesc":"metadesc", "core_catid":"catid", "core_xreference":"null", "asset_id":"null"}], "special": [{"fulltext":"description"}]}', 
	'PlayJoomHelperRoute::getPlayJoomTagRoute', '');