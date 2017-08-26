<?
	class Paging {
		var $sql;
		var $rs;
		var $numrows;
		var $limit;
		var $noofpage;
		var $offset;
		var $page;
		var $style;
		var $parameter;
		var $activestyle;
		var $buttonstyle;
		function Paging($query,$db) {
			$this->offset=0;
			$this->page=1;
			$this->sql=$query;
			$this->rs=$db->query($this->sql);
			$this->numrows=$db->num_rows($this->rs);
		}
		function getNumRows() {
			return $this->numrows;
		}
		function setLimit($no) {
			$this->limit=$no;
		}
		function getLimit() {
			return $this->limit;
		}
		function getNoOfPages() {
			return ceil($this->noofpage=($this->getNumRows()/$this->getLimit()));
		}
		function getPageNo() {
			$str="";
			$str=$str."<table border=0 cellspacing=0 cellpadding=0 width=100%><tr>";
			
//       $str=$str."<td>";
// 			$str=$str."<form name='frmPage' action='".$_SERVER['PHP_SELF']."' method='get'>";
// 			$str=$str."Go to Page &nbsp;<input type='text' name='page' size='3' class='".$this->getStyle()."'>&nbsp;";
// 			$param=split("[& =]",$this->getParameter());
// 			for($i=2;$i<=count($param);$i=$i+2) {
// 				$str=$str."<input type='hidden' name='".$param[$i-1]."' value='".$param[$i]."'>";
// 			}
// 			$str=$str."<input type='submit' name='btnGo' value='Go!' class='".$this->getButtonStyle()."'>";
// 			$str=$str."</form>";
// 			$str=$str."</td>";
			
			$str=$str."<td align='center' valign='top' colspan='2'>";
			$str=$str."<form name='frmPage' action='".$_SERVER['PHP_SELF']."' method='get'>";
      $str=$str."<div class=navigationlinks>Go to Page :&nbsp;";
			
			
      $str=$str."<select name='page' id='page' class='".$this->getStyle()."' >";
			for($i=1;$i<=$this->getNoOfPages();$i++) {
				if($i==$this->getPage()) {
					$str=$str."<option value='".$i."' selected>".$i."</option>";
				}
				else {
					$str=$str."<option value='".$i."' >".$i."</option>";
				}
			}
			$str=$str."</select>";
			
			
			//$str=$str."Go to Page &nbsp;<input type='text' name='page' size='3' class='".$this->getStyle()."'>&nbsp;";
			$param=split("[& =]",$this->getParameter());
			for($i=2;$i<=count($param);$i=$i+2) {
				$str=$str."<input type='hidden' name='".$param[$i-1]."' value='".$param[$i]."'>";
			}
			$str=$str."&nbsp;<input type='submit' name='btnGo' value='Go!' class='".$this->getButtonStyle()."'>";
			$str=$str."</div>";
			
// 			if($this->getPage()>1) {
// 				$str=$str."<a href='".$_SERVER['PHP_SELF']."?page=".($this->getPage()-1).$this->getParameter()."' class='".$this->getStyle()."'>Back</a>&nbsp;";
// 			}
// 			for($i=1;$i<=$this->getNoOfPages();$i++) {
// 				if($i==$this->getPage()) {
// 					$str=$str."<span class='".$this->getActiveStyle()."'>".$i."&nbsp;</span>";
// 				}
// 				else {
// 					$str=$str."<a href='".$_SERVER['PHP_SELF']."?page=".$i.$this->getParameter()."' class='".$this->getStyle()."'>".$i."</a>&nbsp;";
// 				}
// 			}
// 			if($this->getPage()<$this->getNoOfPages()) {
// 				$str=$str."<a href='".$_SERVER['PHP_SELF']."?page=".($this->getPage()+1).$this->getParameter()."' class='normalclass'>Next</a>";
// 			}
			$str=$str."</form></td>";

			$str=$str."</tr></table>";
			//print $str;
			return $str;
		}
		function getOffset($page) {
			if($page>$this->getNoOfPages()) {
				$page=$this->getNoOfPages();
			}
			if($page=="") {
				$this->page=1;
				$page=1;
			}
			else {
				$this->page=$page;
			}
			if($page=="1") {
				$this->offset=0;
				return $this->offset;
			}
			else {
				for($i=2;$i<=$page;$i++) {
					$this->offset=$this->offset+$this->getLimit();
				}
				return $this->offset;
			}
		}
		function getPage() {
			return $this->page;
		}
		function setStyle($style) {
			$this->style=$style;
		}
		function getStyle() {
			return $this->style;
		}
		function setActiveStyle($style) {
			$this->activestyle=$style;
		}
		function getActiveStyle() {
			return $this->activestyle;
		}
		function setButtonStyle($style) {
			$this->buttonstyle=$style;
		}
		function getButtonStyle() {
			return $this->buttonstyle;
		}
		function setParameter($parameter) {
			$this->parameter=$parameter;
		}
		function getParameter() {
			return $this->parameter;
		}
	}
?>
