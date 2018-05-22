<?php
/*
 * @package     Joomla.Plugin
 * @subpackage  Content.pageclass
 * @author      BelKoD www.belkod.ru
 *
 * @copyright   Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
*/

defined('_JEXEC') or die;

use Joomla\CMS\Plugin\CMSPlugin;

jimport('joomla.utilities.utility');

class PlgContentPageclass extends CMSPlugin
{
	/**
	 * Plugin that parse parameter "pageclass_sfx" and adds new class paramemters
	 *
	 * @param   string  $context The context of the content being passed to the plugin.
	 * @param   object  &$row    The article object.  Note $article->text is also available
	 * @param   mixed   &$params The article params
	 * @param   integer $page    The 'page' number
	 *
	 * @return  mixed  Always returns void or true
	 * @since   1.6
	 */
	public function onContentPrepare($context, &$row, &$params, $page = 0)
	{
		// Don't run this plugin when the content is being indexed
		if ($context === 'com_finder.indexer')
		{
			return true;
		}

		$pageclass_sfx = $params->get('pageclass_sfx');
		if(empty($pageclass_sfx)) {
			return true;
		}


		$positions = array();

		$pos = strpos($pageclass_sfx, 'page:');
		if($pos!==false) {
			$positions['page'] = $pos;
		}
		$pos = strpos($pageclass_sfx, 'header:');
		if($pos!==false) {
			$positions['header'] = $pos;
		}
		$pos = strpos($pageclass_sfx, 'body:');
		if($pos!==false) {
			$positions['body'] = $pos;
		}
		$pos = strpos($pageclass_sfx, 'footer:');
		if($pos!==false) {
			$positions['footer'] = $pos;
		}

		if(!empty($positions))
		{
			if (asort($positions, SORT_NUMERIC) === false)
			{
				$params->set('pageclass_sfx', 'plg_content_cageclass_not_work');

				return true;
			}

			$repositions = array();
			foreach ($positions as $key=>$val) {
				$repositions[] = array(
					'key' => $key,
					'val' => $val
				);
			}

			$base_class = '';
			$item = $repositions[0];
			if($item['val']>0) {
				$base_class .= substr($pageclass_sfx, 0, $item['val']);
			}
			$this->getClass($repositions,$positions, $pageclass_sfx);


			$params->set('pageclass_sfx', $base_class.' '.$positions['page']);
			$params->set('headerclass_sfx', $positions['header']);
			$params->set('bodyclass_sfx', $positions['body']);
			$params->set('footerclass_sfx', $positions['footer']);
			unset($positions, $repositions);
		}

		return true;
	}

	protected function getClass(&$repositions, &$positions, $pageclass_sfx)
	{
		if(empty($repositions)) return;
		$current_item = array_shift($repositions);
		$start = $current_item['val']+strlen($current_item['key'])+1;
		$end = 255;
		if(!empty($repositions)) {
			$end = $repositions[0]['val'];
		}
		$positions[$current_item['key']] = (substr($pageclass_sfx, $start, $end-$start));
		$this->getClass($repositions, $positions, $pageclass_sfx);
	}

	protected function array_kshift(&$arr)
	{
		list($k) = array_keys($arr);
		$r  = array($k=>$arr[$k]);
		unset($arr[$k]);
		return $r;
	}
}