<?php
	$xt = new Xtempl();
	if(array_key_exists("custom1",$params) && $params["custom1"]=="horizontal")
	{
		$xt->assign("horizontal_typemenu",true);//horizontal type menu
		$xt->assign("tophorizontal_item",true);//top item for horizontal menu
		$xt->assign("simplehorizontal_item",true);//simple item for horizontal menu
		$horiz=true;
	}	
	else{
		$xt->assign("vertical_typemenu",true);//vertical type menu
		$xt->assign("topvertical_item",true);//top item for vertical menu
		$xt->assign("simplevertical_item",true);//simple item for vertical menu
		$horiz=false;
	}
		
	// create menu nodes arr
	$menuNodes = array();
	$count_menu=0;
	$menuNode = array();
	$menuNode["name"] = "";
	$menuNode["nameType"] = "Text";
	$menuNode["id"] = "1";
	$menuNode["parent"] = "0";
	$menuNode["style"] = "";
	$menuNode["href"] = "mypage.htm";
	$menuNode["table"] = "carsmake";
	$menuNode["type"] = "Leaf";
	$menuNode["linkType"] = "Internal";
	$menuNode["pageType"] = "List";
	$menuNode["openType"] = "None";					 
	// set title		
			$menuNode["title"] = "Carsmake";
	$menuNodes[] = $menuNode;	
	
	$menuNode = array();
	$menuNode["name"] = "";
	$menuNode["nameType"] = "Text";
	$menuNode["id"] = "2";
	$menuNode["parent"] = "0";
	$menuNode["style"] = "";
	$menuNode["href"] = "mypage.htm";
	$menuNode["table"] = "carsmodels";
	$menuNode["type"] = "Leaf";
	$menuNode["linkType"] = "Internal";
	$menuNode["pageType"] = "List";
	$menuNode["openType"] = "None";					 
	// set title		
			$menuNode["title"] = "Carsmodels";
	$menuNodes[] = $menuNode;	
	
	$menuNode = array();
	$menuNode["name"] = "";
	$menuNode["nameType"] = "Text";
	$menuNode["id"] = "3";
	$menuNode["parent"] = "0";
	$menuNode["style"] = "";
	$menuNode["href"] = "mypage.htm";
	$menuNode["table"] = "carsusers";
	$menuNode["type"] = "Leaf";
	$menuNode["linkType"] = "Internal";
	$menuNode["pageType"] = "List";
	$menuNode["openType"] = "None";					 
	// set title		
			$menuNode["title"] = "Carsusers";
	$menuNodes[] = $menuNode;	
	
	$menuNode = array();
	$menuNode["name"] = "";
	$menuNode["nameType"] = "Text";
	$menuNode["id"] = "4";
	$menuNode["parent"] = "0";
	$menuNode["style"] = "";
	$menuNode["href"] = "mypage.htm";
	$menuNode["table"] = "project41_audit";
	$menuNode["type"] = "Leaf";
	$menuNode["linkType"] = "Internal";
	$menuNode["pageType"] = "List";
	$menuNode["openType"] = "None";					 
	// set title		
			$menuNode["title"] = "Project41 Audit";
	$menuNodes[] = $menuNode;	
	
	$menuNode = array();
	$menuNode["name"] = "";
	$menuNode["nameType"] = "Text";
	$menuNode["id"] = "5";
	$menuNode["parent"] = "0";
	$menuNode["style"] = "";
	$menuNode["href"] = "mypage.htm";
	$menuNode["table"] = "carscars";
	$menuNode["type"] = "Leaf";
	$menuNode["linkType"] = "Internal";
	$menuNode["pageType"] = "List";
	$menuNode["openType"] = "None";					 
	// set title		
			$menuNode["title"] = "Carscars";
	$menuNodes[] = $menuNode;	
	
	
	// need to predefine vars
	$nullParent = NULL;
	$rootInfoArr = array("id"=>0, "href"=>"");
	// create treeMenu instance
	$menuRoot = new MenuItem($rootInfoArr, $menuNodes, $nullParent);
	// call xtempl assign, set session params
	$menuRoot->setMenuSession();
	$menuRoot->assignMenuAttrsToTempl($xt);
	$menuRoot->setCurrMenuElem($xt);
	$menuRoot->clearMenuSession();
	
	$xt->assign("mainmenu_block",true);
	$mainmenu=array();
//	Javascript code for menu on page
	$menumessages = "window.TEXT_EXPAND_ALL = '".jsreplace("expand all")."';\r\n";
	$menumessages.= "window.TEXT_COLLAPSE_ALL = '".jsreplace("collapse all")."';\r\n";
	$mainmenu["end"]='
	<script type="text/javascript" language="javascript" src="include/jquery.dropshadow.js"></script>
	<script type="text/javascript" language="javascript" src="include/cookies.js"></script>
	<script>'.$menumessages.
	'	flag = 0;menu1 = null;ul = null;';
	if($horiz)
	{
		//	Horizontal menu
		$mainmenu["end"].='if($("div.Gmenu").length)
				Gmenu();';
	}	
	else
	{
			//	Vertical menu variant 1
		$mainmenu["end"].='if($("div.Vmenu1").length)
			Vmenu1();
		';
	}
	$mainmenu["end"].='</script>';
	
	$count_menu=0;
	foreach($menuRoot->children as $ind=>$val)
		if($val->showAsLink || $val->showAsGroup)
			$count_menu++;
	
	if($count_menu>1)
	{
		$xt->assignbyref("mainmenu_block",$mainmenu);
		$xt->assign("mainmenustyle_block",true);
		$xt->assign("mainmenuiestyle_block",true);
		if(isset($params["quickjump"]))
			$xt->display("mainmenu_quickjump.htm");
		elseif(array_key_exists("custom1",$params) && $params["custom1"]=="horizontal")
			$xt->display("mainmenu_horiz.htm");
		else
			$xt->display("mainmenu.htm");
	}
	//for add ao delete &nbsp; 
?>
