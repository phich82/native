<?php


/**
 * SearchControl builder for search panel on list
 *
 */
class PanelSearchControl extends SearchControl 
{
	// attrs only for search panel
	function getCtrlParamsArr($fName, $recId, $fieldNum=0, $value, $renderHidden = false, $isCached=true) 
	{
		$control = parent::getCtrlParamsArr($fName, $recId, $fieldNum, $value, $renderHidden, $isCached) ;
		
		$control["params"]["additionalCtrlParams"]['isLoadDependent'] = false;
		$control["params"]["additionalCtrlParams"]["style"] = 'width: 75px;';
				
		return $control;
	}
	
	function getCtrlSearchTypeOptions($fName, $selOpt) 
	{
		$options = parent::getCtrlSearchTypeOptions($fName, $selOpt) ;
		$fType = GetEditFormat($fName, $this->tName);	
		
		if ($fType == EDIT_FORMAT_DATE)
		{
			$options.="<OPTION VALUE=\"NOT Equals\" ".(($selOpt=="NOT Equals")?"selected":"").">"."Equals"."</option>";
			$options.="<OPTION VALUE=\"NOT More than\" ".(($selOpt=="NOT More than")?"selected":"").">"."More than ..."."</option>";
			$options.="<OPTION VALUE=\"NOT Less than\" ".(($selOpt=="NOT Less than")?"selected":"").">"."Less than ..."."</option>";
			$options.="<OPTION VALUE=\"NOT Between\" ".(($selOpt=="NOT Between")?"selected":"").">"."Between"."</option>";
			$options.="<OPTION VALUE=\"NOT Empty\" ".(($selOpt=="NOT Empty")?"selected":"").">"."Empty"."</option>";
		}
		elseif ($fType == EDIT_FORMAT_LOOKUP_WIZARD)
		{
			if (Multiselect($fName, $this->tName)){
				$options.="<OPTION VALUE=\"NOT Contains\" ".(($selOpt=="NOT Contains")?"selected":"").">"."Contains"."</option>";
			}else{
				$options.="<OPTION VALUE=\"NOT Equals\" ".(($selOpt=="NOT Equals")?"selected":"").">"."Equals"."</option>";
			}
		}
		elseif ($fType == EDIT_FORMAT_TEXT_FIELD || $fType == EDIT_FORMAT_TEXT_AREA || $fType == EDIT_FORMAT_PASSWORD 
					|| $fType == EDIT_FORMAT_HIDDEN || $fType == EDIT_FORMAT_READONLY)
		{
			$options.="<OPTION VALUE=\"NOT Contains\" ".(($selOpt=="NOT Contains")?"selected":"").">"."Contains"."</option>";
			$options.="<OPTION VALUE=\"NOT Equals\" ".(($selOpt=="NOT Equals")?"selected":"").">"."Equals"."</option>";
			$options.="<OPTION VALUE=\"NOT Starts with\" ".(($selOpt=="NOT Starts with")?"selected":"").">"."Starts with ..."."</option>";
			$options.="<OPTION VALUE=\"NOT More than\" ".(($selOpt=="NOT More than")?"selected":"").">"."More than ..."."</option>";
			$options.="<OPTION VALUE=\"NOT Less than\" ".(($selOpt=="NOT Less than")?"selected":"").">"."Less than ..."."</option>";
			$options.="<OPTION VALUE=\"NOT Between\" ".(($selOpt=="NOT Between")?"selected":"").">"."Between"."</option>";
			$options.="<OPTION VALUE=\"NOT Empty\" ".(($selOpt=="NOT Empty")?"selected":"").">"."Empty"."</option>";
		}
		else
			$options.="<OPTION VALUE=\"NOT Equals\" ".(($selOpt=="NOT Equals")?"selected":"").">"."Equals"."</option>";
		
		return $options;
	}
	/**
	 * For loop assign in window table
	 *
	 * @param int $recId
	 * @param string $fName
	 * @return array
	 */
	function buildSearchCtrlWinBlockArr($recId, $fName) 
	{
		$srchCtrlWinBlock = array();
		// one control with options container attr
		//$filterDivMouseEvents = $this->getFilterDivEvents($recId, $fName);
		$filterRowId = $this->getFilterDivId($recId, $fName).'_win';
		$srchCtrlWinBlock['filterRow_attrs'] = 'id="'.$filterRowId.'" ';//.$filterDivMouseEvents;
		// combo container	
		$srchCtrlWinBlock['srchTypeCont_attrs_win'] = 'id="'.$this->getCtrlComboContId($recId, $fName).'_win"';
		
		return $srchCtrlWinBlock;
	}
	
}


?>