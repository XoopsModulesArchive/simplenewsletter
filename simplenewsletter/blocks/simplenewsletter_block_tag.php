<?php
/**
 * ****************************************************************************
 * simplenewsletter - MODULE FOR XOOPS
 * Copyright (c) Hervé Thouzard of Instant Zero (http://www.instant-zero.com)
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright       Hervé Thouzard of Instant Zero (http://www.instant-zero.com)
 * @license         http://www.fsf.org/copyleft/gpl.html GNU public license
 * @package         simplenewsletter
 * @author 			Hervé Thouzard of Instant Zero (http://www.instant-zero.com)
 *
 * Version : $Id:
 * ****************************************************************************
 */
function simplenewsletter_tag_block_cloud_show($options)
{
	require_once XOOPS_ROOT_PATH.'/modules/tag/blocks/block.php';
	return tag_block_cloud_show($options, 'simplenewsletter');
}
function simplenewsletter_tag_block_cloud_edit($options)
{
	require_once XOOPS_ROOT_PATH.'/modules/tag/blocks/block.php';
	return tag_block_cloud_edit($options);
}
function simplenewsletter_tag_block_top_show($options)
{
	require_once XOOPS_ROOT_PATH.'/modules/tag/blocks/block.php';
	return tag_block_top_show($options, 'simplenewsletter');
}
function simplenewsletter_tag_block_top_edit($options)
{
	require_once XOOPS_ROOT_PATH.'/modules/tag/blocks/block.php';
	return tag_block_top_edit($options);
}
?>