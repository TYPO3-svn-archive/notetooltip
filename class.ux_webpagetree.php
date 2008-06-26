<?php
/***************************************************************
*  Copyright notice
*
*  (c) 1999-2008 Kasper Skaarhoj (kasperYYYY@typo3.com)
*  (c) 2008 Julian Kleinhans (typo3@kj187.de)
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*  A copy is found in the textfile GPL.txt and important notices to the license
*  from the author is found in LICENSE.txt distributed with these scripts.
*
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/

class ux_webPageTree extends webPageTree {
	
	/**
	 * Wrapping icon in browse tree
	 *
	 * @param	string		Icon IMG code
	 * @param	array		Data row for element.
	 * @return	string		Page icon
	 */
	function wrapIcon($icon,&$row)	{
			// If the record is locked, present a warning sign.
		if ($lockInfo=t3lib_BEfunc::isRecordLocked('pages',$row['uid']))	{
			$aOnClick = 'alert('.$GLOBALS['LANG']->JScharCode($lockInfo['msg']).');return false;';
			$lockIcon='<a href="#" onclick="'.htmlspecialchars($aOnClick).'">'.
				'<img'.t3lib_iconWorks::skinImg('','gfx/recordlock_warning3.gif','width="17" height="12"').' title="'.htmlspecialchars($lockInfo['msg']).'" alt="" />'.
				'</a>';
		} else $lockIcon = '';

		
		
		
			// Add title attribute to input icon tag
			
		// ADD TOOLTIP JAVASCRIPT BY JULIAN KLEINHANS
		$thePageIcon = $this->addTagAttributes($icon, $this->titleAttrib.'="'.$this->getTitleAttrib($row).'" '.$this->getTooltipLink($row['uid']));

		
		
		
		
		
			// Wrap icon in click-menu link.
		if (!$this->ext_IconMode)	{
			$thePageIcon = $GLOBALS['TBE_TEMPLATE']->wrapClickMenuOnIcon($thePageIcon,'pages',$row['uid'],0,'&bank='.$this->bank);
		} elseif (!strcmp($this->ext_IconMode,'titlelink'))	{
			$aOnClick = 'return jumpTo(\''.$this->getJumpToParam($row).'\',this,\''.$this->treeName.'\');';
			$thePageIcon='<a href="#" onclick="'.htmlspecialchars($aOnClick).'">'.$thePageIcon.'</a>';
		}

			// Wrap icon in a drag/drop span.
		
		
		
		// ADD TOOLTIP JAVASCRIPT BY JULIAN KLEINHANS
		$dragDropIcon = '<script type="text/javascript" src="'.t3lib_extMgm::extRelPath('notetooltip').'res/wz_tooltip.js"></script>';
		$dragDropIcon .= $this->getTooltip($row['uid']);			
		
		
		
		
		$dragDropIcon .= '<span class="dragIcon" id="dragIconID_'.$row['uid'].'">'.$thePageIcon.'</span>';

			// Add Page ID:
		$pageIdStr = '';
		if ($this->ext_showPageId) { $pageIdStr = '['.$row['uid'].']&nbsp;'; }

			// Call stats information hook
		$stat = '';
		if (is_array($GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['GLOBAL']['recStatInfoHooks']))	{
			$_params = array('pages',$row['uid']);
			foreach ($GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['GLOBAL']['recStatInfoHooks'] as $_funcRef)	{
				$stat.=t3lib_div::callUserFunction($_funcRef,$_params,$this);
			}
		}

		return $dragDropIcon.$lockIcon.$pageIdStr.$stat;
	}	
	
	
	function getTooltip($uid){	
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*','sys_note','pid = '.$uid.' AND deleted = 0','','crdate DESC');
		//echo $GLOBALS['TYPO3_DB']->SELECTquery('*','sys_note','uid = '.$uid.' AND deleted = 0','','crdate DESC').'<br>';
		if($res AND $GLOBALS['TYPO3_DB']->sql_num_rows($res)>=1){
			$content = '';
			while($data = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)){
				$content .= date('d.m.Y',$data['crdate']).'<br/>';
				$content .= $data['author'].' - '.$data['email'].'<br/>';
				$content .= $data['subject'].'<br/>';
				$content .= $data['message'].'<br/>';
				
			}
			 return '<span id="tooltip_'.$uid.'">'.$content.'</span>';
		}		
	}
	
	function getTooltipLink($uid){
		return 'onmouseover="TagToTip(\'tooltip_'.$uid.'\')" onmouseout="UnTip()"';
	}
	
	
}

?>