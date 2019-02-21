<?php
class Chart
{
	var $strSQL;
	var $label2;
    var $numRecordsToShow;
    var $header;
    var $footer;
    var $strLabel;

	var $arrDataLabels = array();
	var $arrDataSeries = array();
	var $arrDataSize = array();
	var $arrAxesColor = array();
	var $arrGaugeColor = array();
	var $arrSeriesSize = array();
	
	var $arrOHLC_high = array();
	var $arrOHLC_low = array();
	var $arrOHLC_open = array();
	var $arrOHLC_close = array();
	
	var $sleg;
	var $scol;
	var $chrt_array = array();
	var $webchart;
	var $cname;
	var $gstrOrderBy;
    
	function Chart(&$ch_array, $param)
	{
		global $field_labels;
		$this->chrt_array=$ch_array;
		$this->numRecordsToShow=10;
		$this->webchart=$param["webchart"];
		$this->cname=$param["cname"];
		$this->gstrOrderBy=$param["gstrOrderBy"];
		if($this->webchart)
			$this->numRecordsToShow=1;
		$this->header = ( (preg_match("/new chart/i", $this->chrt_array['appearance']['head'])) &&
			(!preg_match("/new chart/i", $this->chrt_array['settings']['title']))) ? $this->chrt_array['settings']['title'] : $this->chrt_array['appearance']['head'];
		$this->footer = ( (preg_match("/new chart/i", $this->chrt_array['appearance']['foot'])) &&
			(!preg_match("/new chart/i", $this->chrt_array['settings']['title']))) ? $this->chrt_array['settings']['title'] : $this->chrt_array['appearance']['foot'];    
	    
		for ( $i=0; $i<count($this->chrt_array['parameters'])-1; $i++) 
		{
			if ( $this->chrt_array['parameters'][$i]['name'] != "" ) 
			{
				$this->arrDataSeries[] = ($this->chrt_array['parameters'][$i]['agr_func']) ?
					$this->chrt_array['parameters'][$i]['label'] :
					$this->chrt_array['parameters'][$i]['name'];

				$this->arrDataSize[] = rand(1,300);

				if(!$this->chrt_array['db-based'])
				{
					for ( $j = 0; $j < count($this->chrt_array['fields']); $j++ )
					{
						if($this->chrt_array['parameters'][$i]['name']==$this->chrt_array['fields'][$j]['name'])
							$this->arrDataLabels[]=$this->chart_xmlencode(GetFieldLabel($this->cname,$this->chrt_array['parameters'][$i]['name']));
					}
				}
				else
				{
					$this->arrDataLabels[]=$this->chart_xmlencode(GetFieldLabel($this->cname,$this->chrt_array['parameters'][$i]['name']));
				}
			}
		}
	    
		$this->strLabel = $this->chrt_array['parameters'][count($this->chrt_array['parameters'])-1]['name'];
		for($j = 0; $j<count($this->chrt_array['fields']); $j++)
		{
			if($this->chrt_array['parameters'][count($this->chrt_array['parameters'])-1]['name']==$this->chrt_array['fields'][$j]['name'])
			{
				$this->label2=$this->chart_xmlencode(GetFieldLabel($this->cname,$this->chrt_array['parameters'][count($this->chrt_array['parameters'])-1]['name']));
			}
		}
	    
		$this->arrAxesColor = array("","#1D8BD1","#F1683C","#0065CE");

		if(!$this->webchart)
		{
			$strWhereClause = CalcSearchParam();
		}
		else 
		{
			if($this->chrt_array['db-based'])
				$strTableName="webchart".$this->cname;
			$strWhereClause = CalcSearchParam($this->chrt_array['db-based']);
		}
		if ($strWhereClause) 
		{
			$this->chrt_array['where'] .= ($this->chrt_array['where']) ?
				" AND (" . $strWhereClause . ")" :
				" WHERE (" . $strWhereClause . ")";
		}	
		if(!$this->chrt_array['db-based'])
		{
			if(SecuritySQL("Search"))
			{
				$strWhereClause = whereAdd($strWhereClause, SecuritySQL("Search"));
			}
			$this->strSQL = gSQLWhere($strWhereClause);
		
			$strOrderBy = $this->gstrOrderBy;
			$this->strSQL.= " ".$strOrderBy;

			$strSQLbak=$this->strSQL;
			if(function_exists("BeforeQueryChart")) 
			{
				BeforeQueryChart($this->strSQL,$strWhereClause,$strOrderBy);
			}
			if($strSQLbak == $this->strSQL)
			{
				$this->strSQL=gSQLWhere($strWhereClause);
				$this->strSQL.= " ".$strOrderBy;
			}
		}
		if ($this->cname && $this->chrt_array['db-based']) 
		{
			$this->strSQL = $this->chrt_array['sql'] . $this->chrt_array['where'] . $this->chrt_array['group_by'] . $this->chrt_array['order_by'];
		}
	}
	function write()
	{
		echo "<?xml version=\"1.0\" standalone=\"yes\"?>"."\n";
		echo "<anychart>"."\n";
		echo "<settings>"."\n";
		if($this->chrt_array["appearance"]["sanim"] == "true" ) 
		{
            echo "<animation enabled=\"True\" />"."\n";
        }
        else
		{
            echo "<animation enabled=\"False\" />"."\n";
        }
		echo "</settings>"."\n";
		echo "<charts>"."\n";
		
		$this->write_data();
		$this->write_dps();
		$this->write_chart_settings();
		
		echo "</chart>"."\n";
		echo "</charts>"."\n";
		echo "</anychart>"."\n";
	}
	function write_legend()
	{
		if ( $this->chrt_array['appearance']['slegend'] == "true" ) 
		{
			$this->write_legend_tag();
			$this->write_format();
			echo "<template></template>"."\n";
	         
			echo "<title enabled=\"true\">"."\n";
			echo "<text>".$this->footer."</text>"."\n";
			echo "<font color=\"#".$this->chrt_array["appearance"]["color111"]."\"/>"."\n";
			echo "</title>"."\n";
			echo "<columns_separator enabled=\"false\"/>"."\n";
			echo "<background>"."\n";
			echo "<inside_margin left=\"10\" right=\"10\"/>"."\n";
			echo "</background>"."\n";
			echo "<items>"."\n";
			echo "<item source=\"".$this->sleg."\"/>"."\n";
			echo "</items>"."\n";
			echo "</legend>"."\n";
		}
	}
	function write_format()
	{
		if($this->sleg=="Points")
		{
			echo "<format>{%Icon} {%Name} (".$this->formatCurrency("{%YValue}{numDecimals:".$this->chrt_array["appearance"]["dec"]."}").")</format>"."\n";
		}
	}

	function write_data()
	{
	}
	function write_dps()
	{
	}
	function write_chart_settings()
	{
		echo "<chart_settings>"."\n";
		echo "<title enabled=\"true\" padding=\"15\">"."\n";
		echo "<text>".$this->header."</text>"."\n";
		echo "<font color=\"#".$this->chrt_array["appearance"]["color101"]."\"/>"."\n";
		echo "</title>"."\n";
		$this->write_legend();
		$this->write_axes();
		echo "<chart_background>"."\n";
		$this->write_chart_background();
		echo "</chart_background>"."\n";
		$this->write_plot_background();
		echo "</chart_settings>"."\n";
	}
	function formatCurrency($val)
	{
		global $locale_info;
        if($this->chrt_array['appearance']['scur']=="true")
        {
		    switch($locale_info["LOCALE_ICURRENCY"])
			{
			case 0:
				return $locale_info["LOCALE_SCURRENCY"].$val;
			case 1:
				return $val.$locale_info["LOCALE_SCURRENCY"];
			case 2:
				return $locale_info["LOCALE_SCURRENCY"]." ".$val;
			case 3:
				return $val." ".$locale_info["LOCALE_SCURRENCY"];
			}
		}
		return $val;
	}
	function write_axes_custom()
	{
		echo "<axes>"."\n";
		echo "<y_axis>"."\n";
		if ($this->chrt_array["appearance"]["saxes"] != "true" )
		{
			echo "<line thickness=\"1\" color=\"DarkColor(#".$this->chrt_array["appearance"]["color141"].")\" caps=\"None\"/>"."\n";
			echo "<major_tickmark thickness=\"1\" color=\"DarkColor(#".$this->chrt_array["appearance"]["color141"].")\" caps=\"None\" opacity=\"1\"/>"."\n";
			echo "<minor_tickmark thickness=\"1\" color=\"DarkColor(#".$this->chrt_array["appearance"]["color141"].")\" caps=\"None\" opacity=\"1\"/>"."\n";
		}
		else
		{
			echo "<line thickness=\"1\" color=\"DarkColor(".$this->arrAxesColor[0].")\" caps=\"None\"/>"."\n";
			echo "<major_tickmark thickness=\"1\" color=\"DarkColor(".$this->arrAxesColor[0].")\" caps=\"None\" opacity=\"1\"/>"."\n";
			echo "<minor_tickmark thickness=\"1\" color=\"DarkColor(".$this->arrAxesColor[0].")\" caps=\"None\" opacity=\"1\"/>"."\n";
		}
		
		echo "<title enabled=\"true\">"."\n";
		echo "<text>".$this->arrDataLabels[0]."</text>"."\n";
		if ($this->chrt_array["appearance"]["saxes"] != "true" )
			echo "<font color=\"DarkColor(#".$this->chrt_array["appearance"]["color141"].")\"/>"."\n";
		else
			echo "<font color=\"DarkColor(".$this->arrAxesColor[0].")\"/>"."\n";

		echo "</title>"."\n";
		echo "<zoom enabled=\"".$this->chrt_array["appearance"]["zoom"]."\" allow_drag=\"false\"/>"."\n";

		echo "<labels enabled=\"".$this->chrt_array["appearance"]["sval"]."\" align=\"Inside\">"."\n";
		echo "<format>".$this->formatCurrency("{%Value}{numDecimals:".$this->chrt_array["appearance"]["dec"]."}")."</format>"."\n";
		if ($this->chrt_array["appearance"]["saxes"] != "true" )
			echo "<font color=\"#".$this->chrt_array["appearance"]["color61"]."\" bold=\"false\" italic=\"false\" underline=\"false\" render_as_html=\"false\">"."\n";
		else
			echo "<font color=\"DarkColor(".$this->arrAxesColor[0].")\" bold=\"false\" italic=\"false\" underline=\"false\" render_as_html=\"false\">"."\n";
		
		echo "</font>"."\n";
		echo "</labels>"."\n";
        
		$this->write_Logarithmic();
		$this->write_Stack();
		$this->write_Grid();
        
		echo "</y_axis>"."\n";
        
        $this->write_get_x_axis();
		
		echo "<text>".$this->label2."</text>"."\n";
		echo "<font color=\"DarkColor(#".$this->chrt_array["appearance"]["color131"].")\"/>"."\n";
		echo "</title>"."\n";
		
		echo "<zoom enabled=\"".$this->chrt_array["appearance"]["zoom"]."\" allow_drag=\"false\"/>"."\n";

		echo "<labels enabled=\"".$this->chrt_array["appearance"]["sname"]."\" display_mode=\"normal\">"."\n";
		echo "<font color=\"#".$this->chrt_array["appearance"]["color51"]."\" bold=\"false\" italic=\"false\" underline=\"false\" render_as_html=\"false\">"."\n";
		echo "</font>"."\n";
		echo "<background enabled=\"false\">"."\n";
		echo "<fill enabled=\"false\" />"."\n";
		echo "<border enabled=\"true\" />"."\n";
		echo "</background>"."\n";
		echo "</labels>"."\n";
		echo "</x_axis>"."\n";
		
		echo "<extra>"."\n";
		$this->write_extra();
		echo "</extra>"."\n";
		echo "</axes>"."\n";
	}
	function write_Logarithmic()
	{
		if($this->chrt_array["appearance"]["slog"] == "true" )
		{
			echo "<scale type=\"Logarithmic\" log_base=\"10\"/>"."\n";
		}
	}
	function write_Grid()
	{
		if($this->chrt_array["appearance"]["sgrid"] == "true") 
		{
			echo "<major_grid interlaced=\"True\">"."\n";
			echo "<line color=\"#".$this->chrt_array["appearance"]["color121"]."\" opacity=\"0.7\"/>"."\n";
			echo "<interlaced_fills>"."\n";
			echo "<even><fill color=\"#".$this->chrt_array["appearance"]["color121"]."\" opacity=\"0.1\"/></even>"."\n";
			echo "<odd><fill color=\"#".$this->chrt_array["appearance"]["color121"]."\" opacity=\"0\"/></odd>"."\n";
			echo "</interlaced_fills>"."\n";
			echo "</major_grid>"."\n";
			echo "<minor_grid enabled=\"false\"/>"."\n";
		}
	}
	function write_extra()
	{
		if ($this->chrt_array["appearance"]["saxes"] == "true" )
		{
			for ( $i=1; $i < count($this->arrDataSeries); $i++ ) 
			{
				$position = ( $i % 2 == 0 ) ? "Normal" : "Opposite";
				echo "<y_axis name=\"".$this->arrDataSeries[$i]."\" position=\"".$position."\" enabled=\"true\">"."\n";
				echo "<line thickness=\"1\" color=\"DarkColor(".$this->arrAxesColor[$i].")\" caps=\"None\"/>"."\n";
				echo "<major_tickmark thickness=\"1\" color=\"DarkColor(".$this->arrAxesColor[$i].")\" opacity=\"1\"/>"."\n";
				echo "<minor_tickmark thickness=\"1\" color=\"DarkColor(".$this->arrAxesColor[$i].")\" opacity=\"1\"/>"."\n";
				echo "<minor_grid enabled=\"false\"/>"."\n";
				echo "<major_grid enabled=\"false\"/>"."\n";
				echo "<title enabled=\"true\" align=\"Center\">"."\n";
				echo "<text>".$this->arrDataSeries[$i]."</text>"."\n";
				echo "<font color=\"DarkColor(".$this->arrAxesColor[$i].")\"/>"."\n";
				echo "</title>"."\n";

				echo "<labels align=\"Inside\" enabled=\"".$this->chrt_array["appearance"]["sval"]."\">"."\n";
				echo "<font color=\"DarkColor(".$this->arrAxesColor[$i].")\" bold=\"True\" size=\"9\"/>"."\n";
				echo "<format>".$this->formatCurrency("{%Value}{numDecimals:".$this->chrt_array["appearance"]["dec"]."}")."</format>"."\n";
				echo "</labels>"."\n";
				echo "</y_axis>"."\n";
			}
		}
	}
	function write_chart_background()
	{
		if ($this->chrt_array["appearance"]["color71"]!="") 
		{
			echo "<fill type=\"Gradient\">"."\n";
			echo "<gradient angle=\"90\">"."\n";
			echo "<key position=\"0\" color=\"#".$this->chrt_array["appearance"]["color71"]."\"/>"."\n";
			echo "<key position=\"1\" color=\"DarkColor(#1D8BD1)\" opacity=\"0.5\"/>"."\n";
			echo "</gradient>"."\n";
			echo "</fill>"."\n";
			echo "<corners type=\"Square\"/>"."\n";
		}
		if($this->chrt_array["appearance"]["color91"]!="") 
		{
			echo "<border enabled=\"True\" thickness=\"2\" type=\"Gradient\">"."\n";
			echo "<gradient type=\"Linear\">"."\n";
			echo "<key position=\"0\" color=\"#".$this->chrt_array["appearance"]["color91"]."\" opacity=\"0.5\" />"."\n";
			echo "<key position=\"1\" color=\"DarkColor(#".$this->chrt_array["appearance"]["color91"].")\" opacity=\"1\" />"."\n";
			echo "</gradient>"."\n";
			echo "</border>"."\n";
		}
	}
	function color_series($series)
	{
		if(count($this->arrDataSeries)>1)
		{
			$this->scol="color=\"#".$this->chrt_array["appearance"]["scolor".($series+1)."1"]."\"";
			$this->sleg="Series";
		}
		else
		{
			$this->scol="palette=\"Default\"";
			$this->sleg="Points";
		}
	}
	function get_data($refr)
	{
		global $conn;
		$arrSer = array();
		for ( $i=0; $i < count($this->arrDataSeries); $i++ ) 
		{
			$this->color_series($i);
			$this->arrAxesColor[$i] = "#".$this->chrt_array["appearance"]["scolor".($i+1)."1"];
			$arrSer["series".$i]="<series id= \"".$this->arrDataSeries[$i]."\" name=\"".$this->arrDataLabels[$i]."\" ".$this->scol." ".($i==0?"":(" y_axis=\"".$this->arrDataSeries[$i]."\"")).">"."\n";
		}
		$rs=db_query($this->strSQL,$conn);
		$j = 0;
		while ($row = db_fetch_array($rs)) 
		{
			$j++;
			if ( $j > $this->numRecordsToShow && $this->numRecordsToShow>0) 
			{
				break;
			}
			for ( $i=0; $i < count($this->arrDataSeries); $i++ ) 
			{
				$arrSer["series".$i].=$this->get_point($i,$row)."\n";
			}
			
		}
		for ( $i=0; $i < count($this->arrDataSeries); $i++ ) 
		{
			if($refr)
			{
				echo $this->arrDataSeries[$i]."\n";
				$arrSer["series".$i]=str_replace(array("\\","\n"),array("\\\\","\\n"),$arrSer["series".$i]);
			}
			
			echo $arrSer["series".$i] . "</series>";
			
			if(!$refr || $i<count($this->arrDataSeries)-1)
			{
				echo "\n";
			}
		}
		db_close($conn);
	}
	function chart_xmlencode($str)
	{
		return str_replace(array("&","<",">"),array("&amp;","&lt;","&gt;"),$str);
	}
}

class Chart_Bar extends Chart
{
	var $stacked;
	var $_2d;
	var $bar;
	
	function Chart_Bar(&$ch_array, $param)
	{
		$this->stacked=$param["stacked"];
		$this->_2d=$param["2d"];
		$this->bar=$param["bar"];
		parent::Chart($ch_array, $param);
	}
	function write_data()
	{
		echo "<chart plot_type=\"".$this->plot_type_name()."\">"."\n";
        echo "<data>"."\n";
        $this->get_data(false);
        echo "</data>"."\n";
	}
	function write_dps()
	{
		echo "<data_plot_settings default_series_type=\"Bar\"".$this->series_3d_mode().">"."\n";
        echo "<bar_series group_padding=\"0.5\" ".$this->chart_style_type($this->chrt_array).">"."\n";
		echo "<tooltip_settings enabled=\"True\">"."\n";
		echo "<format>{%Name} - ".$this->formatCurrency("{%YValue}{numDecimals:".$this->chrt_array["appearance"]["dec"]."}")."</format>"."\n";
		echo "</tooltip_settings>"."\n";
        echo $this->write_label_settings();
        echo "</bar_series>"."\n";
        echo "</data_plot_settings>"."\n";
	}
	function write_get_x_axis()
	{
		echo "<x_axis>"."\n";
		echo "<line thickness=\"1\" color=\"DarkColor(#".$this->chrt_array["appearance"]["color131"].")\" caps=\"None\"/>"."\n";
		echo "<title enabled=\"true\" align=\"Center\">"."\n";
	}
	function plot_type_name()
	{
		if(!$this->bar)
		{
			return "CategorizedVertical";
		}
		else
		{
			return "CategorizedHorizontal";
		}
	}
	function series_3d_mode()
	{
		$str="";
		if(!$this->_2d)
		{
			$str= " enable_3d_mode=\"True\"";		
			if($this->bar)
			{
				$str.= " z_aspect=\"1.1\"";
			}
		}
		return $str;
	}
	function chart_style_type()
	{
		if($this->_2d)
		{
			$str="";
			if($this->chrt_array["appearance"]["aqua"] == 1)
			{
				$str=" style=\"AquaLight\"";
			}
			elseif($this->chrt_array["appearance"]["aqua"] == 2)
			{
				$str=" style=\"AquaDark\"";
			}

			if($this->chrt_array["appearance"]["cview"] == 1)
			{
				$str.=" shape_type=\"Cone\"";
			}
			elseif($this->chrt_array["appearance"]["cview"] == 2)
			{
				$str.=" shape_type=\"Cylinder\"";
			}
			elseif($this->chrt_array["appearance"]["cview"] == 3)
			{
				$str.=" shape_type=\"Pyramid\"";
			}
			return $str;
		}
	}
	function write_Stack()
	{
		if($this->stacked)
		{
			if ($this->chrt_array["appearance"]["sstacked"] == "true") 
			{
				echo "<scale mode=\"PercentStacked\" maximum=\"100\" major_interval=\"10\"/>"."\n";
			} 
			else 
			{
				echo "<scale mode=\"Stacked\"/>"."\n";
			}
		}
	}
	function write_label_settings()
	{
		$rotation="";
		$position="";
		$effect="";
		if($this->stacked)
		{
			$rotation=" rotation=\"0\"";
			$position="<position  anchor=\"Center\" halign=\"Center\" valign=\"Center\" padding=\"0\"/>";
			$effect="<font bold=\"False\" color=\"White\">"."\n";
            $effect.="<effects>"."\n";
			$effect.="<drop_shadow enabled=\"True\" opacity=\"0.5\" distance=\"2\" blur_x=\"1\" blur_y=\"1\"/>"."\n";
            $effect.="</effects>"."\n";
            $effect.="</font>"."\n";
            $effect.="<background enabled=\"False\"/>"."\n";
		}
			
		$str="<label_settings enabled=\"".$this->chrt_array["appearance"]["sval"]."\"".$rotation.">"."\n";
		$str.=$position."\n";
		$str.="<format>".$this->formatCurrency("{%YValue}{numDecimals:".$this->chrt_array["appearance"]["dec"]."}");
		$str.="</format>"."\n";
		$str.=$effect."\n";
        $str.="</label_settings>"."\n";
		return $str;
	}
	function get_point($series,$row)
	{
		$value=$row[$this->strLabel];
		if(strlen($value)>20)
		{
			$value=substr($row[$this->strLabel],0,17)."...";
		}
		return "<point id=\"" . $this->chart_xmlencode($value) . "\" name=\"" . $this->chart_xmlencode($value) . "\" y=\"". $this->chart_xmlencode($row[$this->arrDataSeries[$series]]+0). "\"/>";
	}
	function write_axes()
	{
		$this->write_axes_custom();
	}
	function write_legend_tag()
	{
		$posit="";
		$padd="";
		$hgt="";
		$align="";
		if($this->_2d && !$this->bar && !$this->stacked)
		{
			$posit="Bottom";
			$align="align=\"Spread\"";
			$padd="padding=\"15\"";
			$hgt="height=\"20%\"";
		}
		else
		{
			$posit="Right";
		}
		echo "<legend enabled=\"true\" position=\"".$posit."\" ignore_auto_item=\"true\" ".$align." ".$padd." ".$hgt.">"."\n";
	}
	function write_plot_background()
	{
		if($this->chrt_array["appearance"]["color81"]!="")
		{
			echo "<data_plot_background>"."\n";
			echo "<fill color=\"#".$this->chrt_array["appearance"]["color81"]."\" opacity=\"0.3\"/>"."\n";
			echo "</data_plot_background>"."\n";
		}
	}
}
class Chart_Line extends Chart
{
	var $type_line;
	function Chart_Line(&$ch_array, $param)
	{
		$this->type_line=$param["type_line"];
		parent::Chart($ch_array, $param);
	}
	function write_data()
	{
		global $conn;
		echo "<chart plot_type=\"CategorizedVertical\">"."\n";
        echo "<data>"."\n";
        $this->get_data(false);
		echo "</data>"."\n";
	}
	function write_dps()
	{
		echo "<data_plot_settings default_series_type=\"Area\">"."\n";
		echo "<line_series point_padding=\"0.2\" group_padding=\"1\">"."\n";
		echo "<label_settings enabled=\"".$this->chrt_array["appearance"]["sval"]."\">"."\n";
		echo "<background enabled=\"false\"/>"."\n";
		echo "<font color=\"Rgb(45,45,45)\" bold=\"true\" size=\"9\">"."\n";
		echo "<effects enabled=\"true\">"."\n";
		echo "<glow enabled=\"true\" color=\"White\" opacity=\"1\" blur_x=\"1.5\" blur_y=\"1.5\" strength=\"3\"/>"."\n";
		echo "</effects>"."\n";
		echo "</font>"."\n";
		echo "<format>".$this->formatCurrency("{%YValue}{numDecimals:".$this->chrt_array["appearance"]["dec"]."}")."</format>"."\n";
		echo "</label_settings>"."\n";
		echo "<tooltip_settings enabled=\"True\">"."\n";
		echo "<format>";
		echo "Series: {%SeriesName}"."\n";
		echo "Point Name: {%Name}"."\n";
		echo "Value: ".$this->formatCurrency("{%YValue}{numDecimals:".$this->chrt_array["appearance"]["dec"]."}")."\n";
		echo "</format>"."\n";
		echo "<background>"."\n";
		echo "<border type=\"Solid\" color=\"DarkColor(%Color)\"/>"."\n";
		echo "</background>"."\n";
		echo "<font color=\"DarkColor(%Color)\"/>"."\n";
		echo "</tooltip_settings>"."\n";
		echo "<marker_settings enabled=\"true\"/>"."\n";
		echo "<line_style>"."\n";
		echo "<line thickness=\"3\"/>"."\n";
		echo "</line_style>"."\n";
		echo "</line_series>"."\n";
		echo "</data_plot_settings>"."\n";
	}
	function get_point($series,$row)
	{
		$value=$row[$this->strLabel];
		if(strlen($value)>20)
		{
			$value=substr($row[$this->strLabel],0,17)."...";
		}
		return "<point id=\"" . $this->chart_xmlencode($value) . "\" name=\"" . $this->chart_xmlencode($value) . "\" y=\"". $this->chart_xmlencode($row[$this->arrDataSeries[$series]]+0). "\"/>";
	}
	function color_series($series)
	{
		$this->scol="color=\"#".$this->chrt_array["appearance"]["scolor".($series+1)."1"]."\"";
		$this->sleg="Series";
	}
	function write_format()
	{
		echo "<format>{%Icon} {%Name} (".$this->formatCurrency("{%YValue}{numDecimals:".$this->chrt_array["appearance"]["dec"]."}").")</format>"."\n";
	}
	function write_axes()
	{
		$this->write_axes_custom();
	}
	function write_series_type()
	{
		switch($this->type_line)
		{
			case "line": 
				return "Line";
				break;
			case "spline": 
				return "Spline";
				break;
			case "step_line": 
				return "StepLineForward";
				break;
		}
	}
	function write_legend_tag()
	{
		echo "<legend enabled=\"true\" position=\"Bottom\" ignore_auto_item=\"true\" align=\"Spread\" padding=\"15\" height=\"20%\">"."\n";
	}
	function write_get_x_axis()
	{
		echo "<x_axis tickmarks_placement=\"Center\">"."\n";
        echo "<title enabled=\"false\">"."\n";
	}
	function write_Stack()
	{
		return;
	}
	function write_plot_background()
	{
		return;
	}

}
class Chart_Area extends Chart
{
	var $type_area;
	function Chart_Area(&$ch_array, $param)
	{
		parent::Chart($ch_array, $param);
	}
	function write_data()
	{
		global $conn;
		echo "<chart plot_type=\"CategorizedVertical\">"."\n";
        echo "<data>"."\n";
		$this->get_data(false);
		echo "</data>"."\n";
	}
	function write_dps()
	{
		echo "<data_plot_settings default_series_type=\"Area\">"."\n";
		echo "<area_series point_padding=\"0.2\" group_padding=\"1\">"."\n";
		$this->write_label_settings();
		echo "<area_style>"."\n";
		echo "<line enabled=\"true\" thickness=\"2\" color=\"%Color\"/>"."\n";
		echo "<fill color=\"%Color\" opacity=\"0.5\"/>"."\n";
		echo "<states>"."\n";
		echo "<hover>"."\n";
		echo "<line enabled=\"true\" thickness=\"2\" color=\"LightColor(%Color)\"/>"."\n";
		echo "<fill color=\"LightColor(%Color)\" opacity=\"1.0\"/>"."\n";
		echo "</hover>"."\n";
		echo "</states>"."\n";
		echo "</area_style>"."\n";
		echo "<marker_settings enabled=\"True\">"."\n";
		echo "<marker type=\"Circle\" size=\"6\"/>"."\n";
		echo "</marker_settings>"."\n";
		echo "<tooltip_settings enabled=\"True\">"."\n";
		echo "<format>{%Name} - ".$this->formatCurrency("{%YValue}{numDecimals:".$this->chrt_array["appearance"]["dec"]."}")."</format>"."\n";
		echo "<background>"."\n";
		echo "<border color=\"DarkColor(%Color)\"/>"."\n";
		echo "</background>"."\n";
		echo "<font color=\"DarkColor(%Color)\"/>"."\n";
		echo "</tooltip_settings>"."\n";
		echo "</area_series>"."\n";
		echo "</data_plot_settings>"."\n";
	}
	function get_point($series,$row)
	{
		$value=$row[$this->strLabel];
		if(strlen($value)>20)
		{
			$value=substr($row[$this->strLabel],0,17)."...";
		}
		return "<point id=\"" . $this->chart_xmlencode($value) . "\" name=\"" . $this->chart_xmlencode($value) . "\" y=\"". $this->chart_xmlencode($row[$this->arrDataSeries[$series]]+0). "\"/>";
	}
	function color_series($series)
	{
		$this->scol="color=\"#".$this->chrt_array["appearance"]["scolor".($series+1)."1"]."\"";
		$this->sleg="Series";
	}
	function write_axes()
	{
		$this->write_axes_custom();
	}
	function write_label_settings()
	{
		echo "<label_settings enabled=\"true\">"."\n";
		echo "<position anchor=\"CenterBottom\"/>"."\n";
		echo "<background enabled=\"true\">"."\n";
		echo "<border enabled=\"false\"/>"."\n";
		echo "<fill enabled=\"true\" type=\"Solid\" color=\"DarkColor(%Color)\" opacity=\"0.8\"/>"."\n";
		echo "<effects enabled=\"false\"/>"."\n";
		echo "<inside_margin all=\"0\"/>"."\n";
		echo "<corners type=\"Rounded\" all=\"3\"/>"."\n";
		echo "</background>"."\n";
		echo "<font color=\"White\" bold=\"false\"/>"."\n";
		echo "<format>".$this->formatCurrency("{%YValue}{numDecimals:".$this->chrt_array["appearance"]["dec"]."}")."</format>"."\n";
		echo "</label_settings>"."\n";
	}
	function write_legend_tag()
	{
		echo "<legend enabled=\"true\" position=\"Bottom\" ignore_auto_item=\"true\" align=\"Spread\" padding=\"15\" height=\"20%\">"."\n";
	}
	function write_Stack()
	{
		if ($this->chrt_array["appearance"]["sstacked"] == "true") 
		{
			echo "<scale mode=\"PercentStacked\" maximum=\"100\" major_interval=\"10\"/>"."\n";
		} 
		else 
		{
			echo "<scale mode=\"Stacked\"/>"."\n";
		}
	}
	function write_get_x_axis()
	{
			echo "<x_axis>"."\n";
			echo "<line thickness=\"1\" color=\"DarkColor(#".$this->chrt_array["appearance"]["color131"].")\" caps=\"None\"/>"."\n";
			echo "<title enabled=\"true\" align=\"Center\">"."\n";
	}
	function write_plot_background()
	{
		return;
	}

}
class Chart_Pie extends Chart
{
	var $pie;
	function Chart_Pie(&$ch_array, $param)
	{
		$this->pie=$param["pie"];
		parent::Chart($ch_array, $param);
	}
	function write_data()
	{
		echo "<chart plot_type=\"".$this->plot_type_name()."\">"."\n";
		echo "<data>"."\n";
		$this->get_data(false);
		echo "</data>"."\n";
	}
	function write_dps()
	{
		echo "<data_plot_settings enable_3d_mode=\"false\">"."\n";
		echo "<pie_series>"."\n";
		echo "<tooltip_settings enabled=\"true\">"."\n";
		echo "<format>{%Name} Value: ".$this->formatCurrency("{%Value}{numDecimals:".$this->chrt_array["appearance"]["dec"]."}")."\n";
		echo "Percent: {%YPercentOfSeries}{numDecimals:".$this->chrt_array["appearance"]["dec"]."}%</format>"."\n";
		echo "</tooltip_settings>"."\n";
		$this->write_label_settings();
		echo "</pie_series>"."\n";
		echo "</data_plot_settings>"."\n";
	}
	function get_point($series,$row)
	{
		$value=$row[$this->strLabel];
		if(strlen($value)>20)
		{
			$value=substr($row[$this->strLabel],0,17)."...";
		}
		return "<point id=\"" . $this->chart_xmlencode($value) . "\" name=\"" . $this->chart_xmlencode($value) . "\" y=\"". $this->chart_xmlencode($row[$this->arrDataSeries[$series]]+0). "\"/>";
	}
	function write_axes()
	{
		return;
	}
	function plot_type_name()
	{
		if($this->pie)
		{
			return "Pie";
		}
		else
		{
			return "Doughnut";
		}
	}
	function write_label_settings()
	{
		if(count($this->arrDataSeries) > 1)
		{
			echo "<label_settings enabled=\"".$this->chrt_array["appearance"]["sname"]."\">"."\n";
			echo "<background enabled=\"false\"/>"."\n";
			echo "<position anchor=\"Center\" valign=\"Center\" halign=\"Center\" padding=\"20\"/>"."\n";
			echo "<font color=\"White\">"."\n";
			echo "<effects>"."\n";
			echo "<drop_shadow enabled=\"true\" distance=\"2\" opacity=\"0.5\" blur_x=\"2\" blur_y=\"2\"/>"."\n";
			echo "</effects>"."\n";
			echo "</font>"."\n";
			echo "<format>{%YPercentOfSeries}{numDecimals:2}%</format>"."\n";
			echo "</label_settings>"."\n";
		} 
		else 
		{
			echo "<label_settings enabled=\"".$this->chrt_array["appearance"]["sname"]."\" mode=\"Outside\" multi_line_align=\"Center\">"."\n";
			echo "<background enabled=\"false\"/>"."\n";
			echo "<position anchor=\"Center\" valign=\"Center\" halign=\"Center\" padding=\"20\"/>"."\n";
			echo "<font bold=\"false\" />"."\n";
			echo "<format>{%Name} ".$this->formatCurrency("{%Value}{numDecimals:".$this->chrt_array["appearance"]["dec"]."}")." ({%YPercentOfSeries}{numDecimals:2}%)</format>"."\n";
			echo "</label_settings>"."\n";
			echo "<connector color=\"Black\" opacity=\"0.4\"/>"."\n";
		}
	}
	function write_plot_background()
	{
		return;
	}
	function write_legend_tag()
	{
		echo "<legend enabled=\"true\" position=\"Bottom\" ignore_auto_item=\"true\" align=\"Spread\" padding=\"15\" height=\"20%\">"."\n";
	}
}
class Chart_Combined extends Chart
{
	function Chart_Combined(&$ch_array, $param)
	{
		parent::Chart($ch_array, $param);
	}
	function write_data()
	{
		global $conn;
		echo "<chart plot_type=\"CategorizedVertical\">"."\n";
        echo "<data>"."\n";
        echo "<series name=\"".$this->arrDataLabels[0]."\" type=\"Spline\">"."\n";
        $rs=db_query($this->strSQL,$conn);
        $i = 0;
        while ($row = db_fetch_array($rs)) 
        {
            $i++;
            if ( $i > $this->numRecordsToShow && $this->numRecordsToShow>0) {
                break;
            }
            $value=$row[$this->strLabel];
            if(strlen($value)>20)
            {
                $value=substr($row[$this->strLabel],0,17)."...";
            }
            echo "<point name=\"" . $this->chart_xmlencode($value) . "\" y=\"". $this->chart_xmlencode($row[$this->arrDataSeries[0]]+0). "\"/>"."\n";
        }
        echo "</series>"."\n";
        
        if ( count($this->arrDataSeries) > 1 ) {
            echo "<series name=\"".$this->arrDataLabels[1]."\" type=\"SplineArea\">"."\n";
            $rs=db_query($this->strSQL,$conn);
            $i = 0;	
            while ($row = db_fetch_array($rs)) 
            {
                $i++;
                if ( $i > $this->numRecordsToShow && $this->numRecordsToShow>0) {
                    break;
                }		
                $value=$row[$this->strLabel];
                if(strlen($value)>20)
                {
                    $value=substr($row[$this->strLabel],0,17)."...";
                }
                echo "<point name=\"" . $this->chart_xmlencode($value) . "\" y=\"". $this->chart_xmlencode($row[$this->arrDataSeries[1]]+0). "\"/>"."\n";
            }
            echo "</series>";
        }
        
        if ( count($this->arrDataSeries) > 2 ) {
            echo "<series name=\"".$this->arrDataLabels[2]."\" type=\"Bar\">"."\n";
            $rs=db_query($this->strSQL,$conn);
            $i = 0;	
            while ($row = db_fetch_array($rs)) 
            {
                $i++;
                if ( $i > $this->numRecordsToShow && $this->numRecordsToShow>0) {
                    break;
                }		
                $value=$row[$this->strLabel];
                if(strlen($value)>20)
                {
                    $value=substr($row[$this->strLabel],0,17)."...";
                }
                echo "<point name=\"" . $this->chart_xmlencode($value) . "\" y=\"". $this->chart_xmlencode($row[$this->arrDataSeries[2]]+0). "\"/>"."\n";
            }
            echo "</series>"."\n";
        }
        echo "</data>"."\n";
	}
	function write_dps()
	{
		echo "<data_plot_settings default_series_type=\"Bar\">"."\n";
		echo "<bar_series group_padding=\"0.3\">"."\n";
		echo "<label_settings enabled=\"true\">"."\n";
		echo "<format>".$this->formatCurrency("{%YValue}{numDecimals:".$this->chrt_array["appearance"]["dec"]."}")." </format>"."\n";
		echo "</label_settings>"."\n";
		echo "<tooltip_settings enabled=\"True\">"."\n";
		echo "<format>"."\n";
		echo "Series: {%SeriesName}"."\n";
		echo "Point Name: {%Name}"."\n";
		echo "Value: ".$this->formatCurrency("{%Value}{numDecimals:".$this->chrt_array["appearance"]["dec"]."}")."\n";	
		echo "</format>"."\n";	
		echo "</tooltip_settings>"."\n";
		echo "</bar_series>"."\n";
		echo "<line_series>"."\n";
		echo "<label_settings enabled=\"true\">"."\n";
		echo "<format>".$this->formatCurrency("{%YValue}{numDecimals:".$this->chrt_array["appearance"]["dec"]."}")." </format>"."\n";
		echo "</label_settings>"."\n";
		echo "<tooltip_settings enabled=\"True\">"."\n";
		echo "<format>"."\n";
		echo "Series: {%SeriesName}"."\n";
		echo "Point Name: {%Name}"."\n";
		echo "Value: ".$this->formatCurrency("{%Value}{numDecimals:".$this->chrt_array["appearance"]["dec"]."}")."\n";	
		echo "</format>"."\n";
		echo "</tooltip_settings>"."\n";
		echo "<line_style>"."\n";
		echo "<line thickness=\"3\"/>"."\n";
		echo "</line_style>"."\n";
		echo "</line_series>"."\n";
		echo "<area_series>"."\n";
		echo "<label_settings enabled=\"true\">"."\n";
		echo "<format>".$this->formatCurrency("{%YValue}{numDecimals:".$this->chrt_array["appearance"]["dec"]."}")."</format>"."\n";
		echo "</label_settings>"."\n";

		echo "<tooltip_settings enabled=\"true\">"."\n";
		echo "<format>"."\n";
		echo "Series: {%SeriesName}"."\n";
		echo "Point Name: {%Name}"."\n";
		echo "Value: ".$this->formatCurrency("{%Value}{numDecimals:".$this->chrt_array["appearance"]["dec"]."}")." \n";	
		echo "</format>"."\n";
		echo "</tooltip_settings>"."\n";
		echo "<area_style>"."\n";
		echo "<line enabled=\"true\" thickness=\"1\" color=\"DarkColor(%Color)\"/>"."\n";
		echo "<fill opacity=\"0.7\"/>"."\n";
		echo "<states>"."\n";
		echo "<hover>"."\n";
		echo "<fill opacity=\"0.9\"/>"."\n";
		echo "<hatch_fill enabled=\"true\" type=\"Checkerboard\" opacity=\"0.2\"/>"."\n";
		echo "</hover>"."\n";
		echo "</states>"."\n";
		echo "</area_style>"."\n";
		echo "</area_series>"."\n";
		echo "</data_plot_settings>"."\n";
	}
	function write_legend_tag()
	{
		echo "<legend enabled=\"true\" position=\"Bottom\" ignore_auto_item=\"true\" align=\"Spread\" padding=\"15\" height=\"20%\">"."\n";
	}
	function write_get_x_axis()
	{
		echo "<x_axis tickmarks_placement=\"Center\">"."\n";
        echo "<title enabled=\"false\">"."\n";
	}
	function write_axes()
	{
		$this->write_axes_custom();
	}
	function write_Stack()
	{
		if ($this->chrt_array["appearance"]["sstacked"] == "true") 
		{
			echo "<scale mode=\"PercentStacked\" maximum=\"100\" major_interval=\"10\"/>"."\n";
		} 
		else 
		{
			echo "<scale mode=\"Stacked\"/>"."\n";
		}
	}
	function write_plot_background()
	{
		if($this->chrt_array["appearance"]["color81"]!="")
		{
			echo "<data_plot_background>"."\n";
			echo "<fill color=\"#".$this->chrt_array["appearance"]["color81"]."\" opacity=\"0.3\"/>"."\n";
			echo "</data_plot_background>"."\n";
		}
	}
}
class Chart_Funnel extends Chart
{
	var $ftype;
	//0 - треугольник, 1 - конус, 2 - пирамида
	
	var $inver;
	//флаг инверсии
	
	function Chart_Funnel(&$ch_array, $param)
	{
		$this->ftype=$param["funnel_type"]; 
		$this->inver=$param["funnel_inv"]; 
		parent::Chart($ch_array, $param);
	}
	function write_data()
	{
		echo "<chart plot_type=\"Funnel\">"."\n";
        echo "<data>"."\n";
        $this->get_data(false);
        echo "</data>"."\n";
	}
	function write_dps()
	{
		echo "<data_plot_settings ".$this->series_3d_mode().">"."\n";
		$this->funnel_series();
		echo "<connector enabled=\"true\" color=\"Black\" opacity=\"0.5\"/>"."\n";
    	echo "<label_settings enabled=\"true\">"."\n";
		echo "<animation enabled=\"true\" type=\"SideFromRight\" show_mode=\"Smoothed\" start_time=\"0.3\" duration=\"2\" interpolation_type=\"Back\"/>"."\n";
		echo "<position padding=\"20\"/>"."\n";
		echo "<format>{%Name}, ".$this->formatCurrency("{%Value}{numDecimals:".$this->chrt_array["appearance"]["dec"]."}")."</format>"."\n";
		echo "<font bold=\"true\"/>"."\n";
		echo "</label_settings>"."\n";
		echo "<tooltip_settings enabled=\"true\">"."\n";
		echo "<format>{%Name} - ".$this->formatCurrency("{%Value}{numDecimals:".$this->chrt_array["appearance"]["dec"]."}")."\n";
		echo "</format>"."\n";
		echo "<background>"."\n";
		echo "<corners type=\"Rounded\" all=\"3\"/>"."\n";
		echo "</background>"."\n";
		echo "<font bold=\"false\"/>"."\n";
		echo "</tooltip_settings>"."\n";
		echo "<funnel_style>"."\n";
		echo "<states>"."\n";
		echo "<hover>"."\n";
		echo "<fill color=\"%Color\"/>"."\n";
		echo "<hatch_fill enabled=\"true\" type=\"Percent50\" color=\"White\" opacity=\"0.3\"/>"."\n";
		echo "</hover>"."\n";
		echo "<selected_hover>"."\n";
		echo "<fill color=\"%Color\"/>"."\n";
		echo "<hatch_fill type=\"Checkerboard\" color=\"#404040\" opacity=\"0.1\"/>"."\n";
		echo "</selected_hover>"."\n";
		echo "<selected_normal>"."\n";
		echo "<fill color=\"%Color\"/>"."\n";
		echo "<hatch_fill type=\"Checkerboard\" color=\"Black\" opacity=\"0.1\"/>"."\n";
		echo "</selected_normal>"."\n";
		echo "</states>"."\n";
		echo "</funnel_style>"."\n";
		echo "</funnel_series>"."\n";
        echo "</data_plot_settings>"."\n";
	}
	function series_3d_mode()
	{
		$str="";
		if($this->ftype>0) 
		{
			$str= " enable_3d_mode=\"True\"";		
		}
		return $str;
	}
	function get_point($series,$row)
	{
		$value=$row[$this->strLabel];
		if(strlen($value)>20)
		{
			$value=substr($row[$this->strLabel],0,17)."...";
		}
		return "<point id=\"" . $this->chart_xmlencode($value) . "\" name=\"" . $this->chart_xmlencode($value) . "\" y=\"". $this->chart_xmlencode($row[$this->arrDataSeries[$series]]+0). "\"/>";
	}
	function write_axes()
	{
		return;
	}
	function write_legend_tag()
	{
		echo "<legend enabled=\"true\" position=\"Bottom\" ignore_auto_item=\"true\" align=\"Spread\" padding=\"15\" height=\"20%\">"."\n";
	}
	function write_plot_background()
	{
		if($this->chrt_array["appearance"]["color81"]!="")
		{
			echo "<data_plot_background>"."\n";
			echo "<fill color=\"#".$this->chrt_array["appearance"]["color81"]."\" opacity=\"0.3\"/>"."\n";
			echo "</data_plot_background>"."\n";
		}
	}
	function funnel_series()
	{
		if($this->inver)
			$inv="inverted=\"false\"";
		else
			$inv="inverted=\"true\"";
			
		if($this->ftype<2)
		{
			echo "<funnel_series ".$inv." neck_height=\"0\" min_width=\"0\" padding=\"0\" fit_aspect=\"0.9\">"."\n";
			echo "<animation enabled=\"true\" start_time=\"0.3\" duration=\"2\" type=\"SideFromLeft\" animate_opacity=\"false\" interpolation_type=\"Elastic\" show_mode=\"Smoothed\"/>"."\n";
		}
		else
		{
			echo "<funnel_series ".$inv." neck_height=\"0\" fit_aspect=\"1\" min_width=\"0\" padding=\"0\" mode=\"Square\">"."\n";
			echo "<animation enabled=\"true\" start_time=\"0.3\" duration=\"2\" type=\"SideFromTop\" animate_opacity=\"true\" interpolation_type=\"Bounce\" show_mode=\"Smoothed\" />"."\n";
		}
	}
}
class Chart_Bubble extends Chart
{
	var $_2d;
	var $oppos;
	
	function Chart_Bubble(&$ch_array, $param)
	{
		//$this->strLabel="";
		$this->_2d=$param["2d"];
		$this->oppos=$param["oppos"];
		parent::Chart($ch_array, $param);
	}
	function write_data()
	{
		echo "<chart ".$this->char_type().">"."\n";
        echo "<data>"."\n";
        $this->get_data(false);
        echo "</data>"."\n";
	}
	function write_dps()
	{
		echo "<data_plot_settings default_series_type=\"Bubble\">"."\n";
		echo "<bubble_series maximum_bubble_size=\"40%\" ".$this->style_chart().">"."\n";
		echo "<tooltip_settings enabled=\"true\">"."\n";
		echo "<format>"."\n";
		echo "Series: {%SeriesName}"."\n";
		echo "Point Name: {%Name}"."\n";
		echo "Value: ".$this->formatCurrency("{%Value}{numDecimals:".$this->chrt_array["appearance"]["dec"]."}")."\n";
		echo "Bubble Size: {%BubbleSize}"."\n";
		echo "</format>"."\n";
		echo "</tooltip_settings>"."\n";
		echo "<bubble_style>"."\n";
		
		$this->fill_oppos();
		
		echo "<states>"."\n";
		echo "<hover>"."\n";
		echo "<border thickness=\"2\"/>"."\n";
		echo "<fill color=\"LightColor(%Color)\"/>"."\n";
		echo "</hover>"."\n";
		echo "</states>"."\n";
		echo "</bubble_style>"."\n";
		echo "</bubble_series>"."\n";
		echo "</data_plot_settings>"."\n";
	}
	function write_legend_tag()
	{
		echo "<legend enabled=\"true\" position=\"Bottom\" ignore_auto_item=\"true\" align=\"Spread\" padding=\"15\" height=\"20%\">"."\n";
	}
	function write_plot_background()
	{
		if($this->chrt_array["appearance"]["color81"]!="")
		{
			echo "<data_plot_background>"."\n";
			echo "<fill color=\"#".$this->chrt_array["appearance"]["color81"]."\" opacity=\"0.3\"/>"."\n";
			echo "</data_plot_background>"."\n";
		}
	}
	function char_type()
	{
		if($this->strLabel=="")
			$str="plot_type=\"CategorizedBySeriesHorizontal\"";
		else 
		{
			if($this->_2d) 
				$str="type=\"CategorizedVertical\"";
			else
				$str="type=\"Categorized\"";
		}
		return $str;
	}
	function fill_oppos()
	{
		if($this->_2d) 
		{
			echo "<fill opacity=\"".$this->oppos."\"/>"."\n";
			echo "<border thickness=\"2\"/>"."\n";
		}
	}
	function style_chart()
	{
		if(!$this->_2d) 
			return "style=\"Aqua\"";
	
	}
	function get_point($series,$row)
	{
		$value=$row[$this->strLabel];
		if(strlen($value)>20)
		{
			$value=substr($row[$this->strLabel],0,17)."...";
		}
		$this->arrSeriesSize[$series]=20;
			
		$id_name=($value!="") ? "id=\"" . $this->chart_xmlencode($value) . "\" name=\"" . $this->chart_xmlencode($value) . "\"" : "";
		return "<point ".$id_name." y=\"". $this->chart_xmlencode($row[$this->arrDataSeries[$series]]+0). "\" size=\"" . $this->arrSeriesSize[$series]. "\"/>";
	}
	function write_axes()
	{
		echo "<axes>"."\n";
		echo "<y_axis position=\"Opposite\">"."\n";
		echo "<line thickness=\"1\" color=\"DarkColor(#".$this->chrt_array["appearance"]["color141"].")\" caps=\"None\"/>"."\n";
		echo "<major_tickmark thickness=\"1\" color=\"DarkColor(#".$this->chrt_array["appearance"]["color141"].")\" caps=\"None\" opacity=\"1\"/>"."\n";
		echo "<scale major_interval=\"1\" mode=\"Overlay\"/>"."\n";
		
		echo "<labels enabled=\"".$this->chrt_array["appearance"]["sval"]."\" align=\"Inside\">"."\n";
		echo "<format>".$this->formatCurrency("{%Value}{numDecimals:".$this->chrt_array["appearance"]["dec"]."}")."</format>"."\n";
		echo "<font color=\"#".$this->chrt_array["appearance"]["color61"]."\" bold=\"false\" italic=\"false\" underline=\"false\" render_as_html=\"false\">"."\n";

		echo "</font>"."\n";
		echo "</labels>"."\n";		
		echo "<title enabled=\"true\">"."\n";
		echo "<text>".$this->arrDataLabels[0]."</text>"."\n";
		echo "<font color=\"DarkColor(#".$this->chrt_array["appearance"]["color141"].")\"/>"."\n";
		echo "</title>"."\n";
		echo "<minor_grid enabled=\"false\"/>"."\n";
		echo "<major_grid enabled=\"true\"/>"."\n";
		echo "<minor_tickmark enabled=\"false\"/>"."\n";
		
		$this->write_Grid();

		echo "</y_axis>"."\n";
		
		echo "<zoom enabled=\"".$this->chrt_array["appearance"]["zoom"]."\" allow_drag=\"false\"/>"."\n";
		
		echo "<x_axis tickmarks_placement=\"Center\">"."\n";
		echo "<line thickness=\"1\" color=\"DarkColor(#".$this->chrt_array["appearance"]["color131"].")\" caps=\"None\"/>"."\n";
		echo "<title enabled=\"true\" align=\"Center\">"."\n";
		echo "<text>".$this->label2."</text>"."\n";
		echo "<font color=\"DarkColor(#".$this->chrt_array["appearance"]["color131"].")\"/>"."\n";
		echo "</title>"."\n";
		echo "<labels enabled=\"".$this->chrt_array["appearance"]["sname"]."\" display_mode=\"normal\">"."\n";
		echo "<font color=\"#".$this->chrt_array["appearance"]["color51"]."\" bold=\"false\" italic=\"false\" underline=\"false\" render_as_html=\"false\">"."\n";
		echo "</font>"."\n";
		echo "<background enabled=\"false\">"."\n";
		echo "<fill enabled=\"false\" />"."\n";
		echo "<border enabled=\"true\" />"."\n";
		echo "</background>"."\n";
		echo "</labels>"."\n";
		echo "<scale inverted=\"True\"/>"."\n";
		echo "</x_axis>"."\n";
		echo "</axes>"."\n";
	}
}
class Chart_Gauge extends Chart
{
	var $type_gauge;
	var $orientation;
	var $start_angle;
	var $sweep_angle;
	var $scale_min;
	var $scale_max;
	var $major_interval;
	var $minor_interval;

	function Chart_Gauge(&$ch_array, $param)
	{
		$this->type_gauge=$param["type_gauge"];
		$this->orientation=$param["orientation"];
		parent::Chart($ch_array, $param);
	}
	function write()
	{
		echo "<?xml version=\"1.0\" standalone=\"yes\"?>"."\n";
		echo "<anychart>"."\n";
		echo "<settings>"."\n";
		if($this->chrt_array["appearance"]["sanim"] == "true" ) 
		{
            echo "<animation enabled=\"True\" />"."\n";
        }
        else
		{
            echo "<animation enabled=\"False\" />"."\n";
        }
		echo "</settings>"."\n";
		echo "<gauges>"."\n";
		echo "<gauge>"."\n";
		$this->write_chart_settings();
		echo "<".$this->type_gauge." orientation=\"".$this->orientation."\" name=\"".$this->arrDataSeries[0]."_gauge\">"."\n";
		$this->gauge_style();
		$this->write_data();
		echo "</".$this->type_gauge.">"."\n";
		echo "</gauge>"."\n";
		echo "</gauges>"."\n";
		echo "</anychart>"."\n";
	}
	function gauge_style()
	{
		if($this->type_gauge!="circular")
		{
			echo "<styles>"."\n";
			echo "<color_range_style name=\"anychart_default\" align=\"Outside\" padding=\"3\" start_size=\"15\" end_size=\"15\">"."\n";
			echo "<fill type=\"Gradient\">"."\n";
			echo "<gradient>"."\n";
			echo "<key color=\"Blend(%Color,DarkColor(%Color),0.5)\"/>"."\n";
			echo "<key color=\"%Color\"/>"."\n";
			echo "<key color=\"Blend(%Color,DarkColor(%Color),0.5)\"/>"."\n";
			echo "</gradient>"."\n";
			echo "</fill>"."\n";
			echo "<border enabled=\"true\" color=\"DarkColor(%Color)\" opacity=\"0.8\"/>"."\n";
			echo "</color_range_style>"."\n";
			echo "</styles>"."\n";
		}
	}
	function write_data()
	{
		echo "<pointers>"."\n";
		$this->get_data(false);
		$this->pointer_label();
		$this->pointer_style();
		echo "<animation enabled=\"true\" start_time=\"=0\" duration=\"1\" interpolation_type=\"Elastic\"/>"."\n";
		echo "</pointer>"."\n";
		echo "</pointers>"."\n";
		$this->get_frame();
		$this->get_axis();
	}
	function write_chart_settings()
	{
		echo "<chart_settings>"."\n";
		echo "<title enabled=\"true\" padding=\"15\">"."\n";
		echo "<text>".$this->header."</text>"."\n";
		echo "<font color=\"#".$this->chrt_array["appearance"]["color101"]."\"/>"."\n";
		echo "</title>"."\n";
		echo "</chart_settings>"."\n";
	}
	function get_data($refr)
	{
		global $conn,$g_orderindexes;
		$i=0;
		$this->start_angle=30;
		$this->sweep_angle=300;
		$this->arrGaugeColor[]=array(-1000,0,"#B2DBE8");
		$this->arrGaugeColor[]=array(0,1000,"#FFCC96");
		$this->scale_min=-1000;
		$this->scale_max=1000;
		$this->major_interval=200;
		$this->minor_interval=50;
		
		$p=strrpos(strtolower($this->strSQL),"order by");
		if($p>0)
		{
			$ob="ORDER BY";
			foreach($g_orderindexes as $ind=>$val)
			{
				$ob.=" ".$val[0]." ";
				if($val[1]=="ASC")
					$ob.="DESC";
				else
					$ob.="ASC";
				if($ind+1!=count($g_orderindexes))
					$ob.=",";
			}
			
			$this->strSQL=substr($this->strSQL,0,$p).$ob;
		}
		$rs=db_query($this->strSQL,$conn);
		if($row = db_fetch_array($rs)) 
		{
			$arrSer["series".$i].="<pointer name=\"".$this->arrDataSeries[$i]."_point\" type=\"".$this->pointer_type()."\" value=\"".$this->chart_xmlencode($row[$this->arrDataSeries[$i]]+0)."\" color=\"#75B7E1\">"."\n";
		}
		if($refr)
		{
			echo $this->arrDataSeries[$i]."\n";
			echo $this->chart_xmlencode($row[$this->arrDataSeries[$i]]+0);
		}
		else
			echo $arrSer["series".$i];
		
		if(!$refr || $i<count($this->arrDataSeries)-1)
		{
			echo "\n";
		}

		db_close($conn);
	}
	function pointer_type()
	{
		if($this->type_gauge=="circular")
			return "Needle";
		else
			return "Marker";
	}
	function pointer_label()
	{
		echo "<label enabled=\"true\">"."\n";
		echo "<format>".$this->formatCurrency("{%Value}{numDecimals:".$this->chrt_array["appearance"]["dec"]."}")."</format>"."\n";
		if($this->type_gauge=="circular")
		{
			echo "<position placement_mode=\"ByPoint\" x=\"50\" y=\"90\" valign=\"Center\" halign=\"Center\"/>"."\n";
			echo "<background>"."\n";
			echo "<fill type=\"Solid\" color=\"White\" opacity=\"0.8\"/>"."\n";
			echo "<border type=\"Solid\" color=\"Black\" opacity=\"0.2\"/>"."\n";
			echo "<corners type=\"Rounded\" all=\"5\"/>"."\n";
			echo "<effects enabled=\"false\"/>"."\n";
			echo "</background>"."\n";
		}
				else
		{
			
			$this->get_position();
			echo "<background enabled=\"false\"/>"."\n";
		}
		echo "</label>"."\n";
	}
	function get_position()
	{
		if($this->orientation=="Vertical")
			echo "<position placement_mode=\"ByAnchor\" valign=\"Center\" halign=\"Right\" padding=\"15\"/>"."\n";
		else
			echo "<position placement_mode=\"ByAnchor\" valign=\"Bottom\" halign=\"Center\" padding=\"16\"/>"."\n";
	}
	function pointer_style()
	{
		if($this->type_gauge=="circular")
		{
			echo "<needle_pointer_style base_radius=\"-50\">"."\n";
			echo "<cap>"."\n";
			echo "<background>"."\n";
			echo "<fill type=\"Gradient\">"."\n";
			echo "<gradient type=\"Linear\" angle=\"45\">"."\n";
			echo "<key color=\"#D3D3D3\"/>"."\n";
			echo "<key color=\"#6F6F6F\"/>"."\n";
			echo "</gradient>"."\n";
			echo "</fill>"."\n";
			echo "<border color=\"Black\" opacity=\"0.9\"/>"."\n";
			echo "</background>"."\n";
			echo "<effects enabled=\"true\">"."\n";
			echo "<bevel enabled=\"true\" distance=\"2\" shadow_opacity=\"0.6\" highlight_opacity=\"0.6\"/>"."\n";
			echo "<drop_shadow enabled=\"true\" distance=\"1.5\" blur_x=\"2\" blur_y=\"2\" opacity=\"0.4\"/>"."\n";
			echo "</effects>"."\n";
			echo "</cap>"."\n";
			echo "<effects enabled=\"true\">"."\n";
			echo "<drop_shadow enabled=\"true\" distance=\"2\" opacity=\"0.5\" blur_x=\"2\" blur_y=\"2\" color=\"Black\"/>"."\n";
			echo "<bevel enabled=\"true\" distance=\"2\" shadow_opacity=\"0.5\" highlight_opacity=\"0.5\"/>"."\n";
			echo "</effects>"."\n";
			echo "</needle_pointer_style>"."\n";
		}
		else
		{
			echo "<marker_pointer_style align=\"Outside\" padding=\"18.5\"/>"."\n";
		}
	}
	function get_frame()
	{
		if($this->type_gauge=="circular")
		{
			echo "<frame type=\"Rectangular\">"."\n";
			echo "<inner_stroke enabled=\"false\"/>"."\n";
			echo "<outer_stroke enabled=\"false\"/>"."\n";
			echo "<corners type=\"Rounded\" all=\"15\"/>"."\n";
			echo "<background>"."\n";
			echo "<border enabled=\"true\" color=\"".$this->chrt_array["appearance"]["color81"]."\" opacity=\"0.5\"/>"."\n";
			echo "</background>"."\n";
			echo "</frame>"."\n";
		}
	}
	function get_axis()
	{
		$pos="";
		if($this->type_gauge=="circular")
		{
			$pos="align=\"Inside\" padding=\"40\"";
			echo "<axis start_angle=\"".$this->start_angle."\" sweep_angle=\"".$this->sweep_angle."\">"."\n";
		}
		else		
		{
			echo "<axis>"."\n";
		}
		echo "<scale minimum=\"".$this->scale_min."\" maximum=\"".$this->scale_max."\" major_interval=\"".$this->major_interval."\" minor_interval=\"".$this->minor_interval."\"/>"."\n";
		$this->get_tickmark();
		
			echo "<color_ranges>"."\n";
		
		foreach($this->arrGaugeColor as $ind=>$val)
		{
			echo "<color_range start=\"".$val[0]."\" end=\"".$val[1]."\" color=\"".$val[2]."\" ".$pos.">"."\n";
			if($this->type_gauge=="circular")
			{
				echo "<border enabled=\"true\" color=\"Black\" opacity=\"0.2\"/>"."\n";
				echo "<fill opacity=\"0.7\"/>"."\n";
			}
			echo "</color_range>"."\n";
		}
		
		echo "</color_ranges>"."\n";
		
		$this->get_scale_bar();
		$this->get_labels();
		echo "</axis>"."\n";
	}
	function get_tickmark()
	{
		if($this->type_gauge!="circular")
		{
			echo "<major_tickmark shape=\"Rectangle\" width=\"1.3\" length=\"10\" align=\"Center\" padding=\"0\">"."\n";
			echo "<fill type=\"Solid\" color=\"White\"/>"."\n";
			echo "<border enabled=\"true\" color=\"#494949\" opacity=\"0.5\"/>"."\n";
			echo "</major_tickmark>"."\n";
			echo "<minor_tickmark shape=\"Line\" align=\"Center\" length=\"7\">"."\n";
			echo "<border enabled=\"true\" color=\"#494949\" opacity=\"1\"/>"."\n";
			echo "</minor_tickmark>"."\n";
			echo "<scale_bar enabled=\"false\"/>"."\n";
			echo "<scale_line enabled=\"false\"/>"."\n";
		}
	}
	function get_scale_bar()
	{
		if($this->type_gauge=="circular")
		{
			echo "<scale_bar>"."\n";
			echo "<fill color=\"Rgb(200,200,200)\"/>"."\n";
			echo "</scale_bar>"."\n";
		}
	}
	function get_labels()
	{
		if($this->type_gauge!="circular")
		{
			echo "<labels align=\"Inside\" padding=\"1\">"."\n";
			echo "<format>".$this->formatCurrency("{%Value}{numDecimals:".$this->chrt_array["appearance"]["dec"]."}")."</format>"."\n";
			echo "</labels>"."\n";
		}
	}
}	
class Chart_Ohlc extends Chart
{
	var $ohcl_type;
	function write()
	{
		echo "<?xml version=\"1.0\" standalone=\"yes\"?>"."\n";
		echo "<anychart>"."\n";
		echo "<charts>"."\n";
		$this->write_data();
		$this->write_dps();
		$this->write_chart_settings();
		
		echo "</chart>"."\n";
		echo "</charts>"."\n";
		echo "</anychart>"."\n";
	}
	function Chart_Ohlc(&$ch_array, $param)
	{
		$this->ohcl_type=$param["ohcl_type"];
		parent::Chart($ch_array, $param);
	}
	function write_data()
	{
		echo "<chart plot_type=\"CategorizedVertical\">"."\n";
        echo "<data>"."\n";
        $this->get_data(false);
        echo "</data>"."\n";
	}
	function get_series_type()
	{
		if($this->ohcl_type=="ohcl")
			return "OHLC";
		else
			return "Candlestick";
		
	}
	function write_dps()
	{
		
		echo "<data_plot_settings default_series_type=\"".$this->get_series_type()."\">"."\n";
		$this->get_ohcl_tooltip();
        echo "</data_plot_settings>"."\n";
        $this->ohls_styles();
	}
	function ohls_styles()
	{
		echo "<styles>"."\n";
		for ( $i=0; $i < count($this->arrDataSeries); $i++ ) 
		{
			if($this->ohcl_type=="ohcl")
			{
				echo "<ohlc_style name=\"style".($i+1)."\">"."\n";
				$attr="line thickness=\"1\"";
			}
			else
			{
				echo "<candlestick_style name=\"style".($i+1)."\">"."\n";
				$attr="fill";
			}
			echo "<up>"."\n";
			echo "<".$attr." color=\"".$this->arrOHLC_color_up[$i]."\"/>"."\n";
			echo "</up>"."\n";
			echo "<down>"."\n";
			echo "<".$attr." color=\"".$this->arrOHLC_color_down[$i]."\"/>"."\n";
			echo "</down>"."\n";
			echo "<states>"."\n";
			echo "<hover>"."\n";
			echo "<up>"."\n";
			echo "<".$attr." color=\"LightColor(".$this->arrOHLC_color_up[$i].")\"/>"."\n";
			echo "</up>"."\n";
			echo "<down>"."\n";
			echo "<".$attr." color=\"LightColor(".$this->arrOHLC_color_down[$i].")\"/>"."\n";
			echo "</down>"."\n";
			echo "</hover>"."\n";
			echo "</states>"."\n";
			if($this->ohcl_type=="ohcl")
				echo "</ohlc_style>"."\n";
			else
				echo "</candlestick_style>"."\n";
		}
		echo "</styles>"."\n";
	}
	function get_ohcl_tooltip()
	{
		if($this->ohcl_type=="ohcl")
			echo "<ohlc_series>"."\n";
		else
			echo "<candlestick_series>"."\n";
		echo "<tooltip_settings enabled=\"True\">"."\n";
		echo "<format>";
		echo "O: ".$this->formatCurrency("{%Open}{numDecimals:".$this->chrt_array["appearance"]["dec"]."}").""."\n";
		echo "H: ".$this->formatCurrency("{%High}{numDecimals:".$this->chrt_array["appearance"]["dec"]."}").""."\n";
		echo "L: ".$this->formatCurrency("{%Low}{numDecimals:".$this->chrt_array["appearance"]["dec"]."}").""."\n";
		echo "C: ".$this->formatCurrency("{%Close}{numDecimals:".$this->chrt_array["appearance"]["dec"]."}")."";
		echo "</format>"."\n";
		echo "</tooltip_settings>"."\n";
		if($this->ohcl_type=="ohcl")
			echo "</ohlc_series>"."\n";
		else
			echo "</candlestick_series>"."\n";
	}
	function write_chart_settings()
	{
		echo "<chart_settings>"."\n";
		echo "<title enabled=\"false\"/>"."\n";
		echo "<axes>"."\n";
		echo "<y_axis>"."\n";
		
		$this->write_Logarithmic();
		
		echo "<title>"."\n";
		echo "<text>".$this->arrDataLabels[0]."</text>"."\n";
		echo "</title>"."\n";
		echo "</y_axis>"."\n";
		echo "<x_axis>"."\n";
		echo "<title>"."\n";
		echo "<text>".$this->label2."</text>"."\n";
		echo "</title>"."\n";
		echo "</x_axis>"."\n";
		echo "</axes>"."\n";
		echo "</chart_settings>"."\n";
	}
	function get_data($refr)
	{
		global $conn;
		$arrSer = array();
		
		$this->arrOHLC_high = array(4,4,5,5,6,6,7,7,8,8,9,9,10,10,11,11,12,12,13,13);
		$this->arrOHLC_low = array(1,1,2,2,3,3,4,4,5,5,6,6,7,7,8,8,9,9,10,10);
		$this->arrOHLC_open = array(2,2,4,4,5,5,6,6,7,7,8,8,9,9,10,10,11,11,12,12);
		$this->arrOHLC_close = array(3,3,3,3,4,4,5,5,6,6,7,7,8,8,9,9,10,10,11,11);
		
		for ( $i=0; $i < count($this->arrDataSeries); $i++ ) 
		{
		//	$this->scol="color=\"#".$this->chrt_array["appearance"]["scolor".($i+1)."1"]."\"";
			$this->arrOHLC_color_up[$i] = "Red";
			$this->arrOHLC_color_down[$i] = "Green";
			$arrSer["series".$i]="<series id=\"".$this->arrDataSeries[$i]."\" name=\"".$this->arrDataLabels[$i]."\" style=\"style".($i+1)."\">"."\n";
		}
		$rs=db_query($this->strSQL,$conn);
		$j = 0;
		while ($row = db_fetch_array($rs)) 
		{
			$j++;
			if ( $j > $this->numRecordsToShow && $this->numRecordsToShow>0) 
			{
				break;
			}
			for ( $i=0; $i < count($this->arrDataSeries); $i++ ) 
			{
				$arrSer["series".$i].=$this->get_point($i,$row,$j-1)."\n";
			}
			
		}
		for ( $i=0; $i < count($this->arrDataSeries); $i++ ) 
		{
			if($refr)
			{
				echo $this->arrDataSeries[$i]."\n";
				$arrSer["series".$i]=str_replace(array("\\","\n"),array("\\\\","\\n"),$arrSer["series".$i]);
			}
			
			echo $arrSer["series".$i] . "</series>";
			
			if(!$refr || $i<count($this->arrDataSeries)-1)
			{
				echo "\n";
			}
		}
		db_close($conn);
	}
	function get_point($i,$row,$j)
	{
		$value=$row[$this->strLabel];
		if(strlen($value)>20)
		{
			$value=substr($row[$this->strLabel],0,17)."...";
		}		
		$str="<point name=\"".$this->chart_xmlencode($value)."\" ";
		$str.="high=\"".($this->arrOHLC_high[$j]+$i*5)."\"  low=\"".($this->arrOHLC_low[$j]+$i*5)."\" open=\"".($this->arrOHLC_open[$j]+$i*5)."\" close=\"".($this->arrOHLC_close[$j]+$i*5)."\"/>";
		return $str;
	}
	function write_Logarithmic()
	{
		if($this->chrt_array["appearance"]["slog"] == "true" )
		{
			echo "<scale type=\"Logarithmic\" log_base=\"10\"/>"."\n";
		}
	}
}


?>
