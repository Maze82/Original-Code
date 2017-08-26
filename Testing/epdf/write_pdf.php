<?php
include("../config/data.config.php");
include("$LIB_DIR/class.database.php");
include("$LIB_DIR/data.constant.php");
include("$LIB_DIR/functions.library.php");

include("$LIB_DIR/html2fpdf.php");

$db=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db->open() or die($db->error());   
  
  global $SITE_URL;
  
  $c_wattage = $_REQUEST['c_wattage'];
  $c_lamplife = $_REQUEST['c_lamplife'];
  $c_laborcost = $_REQUEST['c_laborcost'];
  $c_lampcost = $_REQUEST['c_lampcost'];
  $lamp_life1 = $_REQUEST['lamp_life1'];
  $wattage = $_REQUEST['wattage'];
  $lamp_life = $_REQUEST['lamp_life'];   
  $labour_cost = $_REQUEST['labour_cost'];
  $c_lampcost_solais = $_REQUEST['c_lampcost_solais'];
  $lamp_life_solais = $_REQUEST['lamp_life_solais'];
  $num_of_lamp = $_REQUEST['num_of_lamp'];
  $ele_cost = $_REQUEST['ele_cost'];
  $use_hour = $_REQUEST['use_hour'];
  $use_day = $_REQUEST['use_day'];
  $a_period = $_REQUEST['a_period'];
  $total_saving = $_REQUEST['total_saving'];
  $payback_year = $_REQUEST['payback_year'];
  if($payback_year=='0')
  $payback_year = '00'; 
  $payback_month = $_REQUEST['payback_month'];
  if($payback_month=='0')
  $payback_month = '00'; 
  $payback_days = $_REQUEST['payback_days'];
  if($payback_days=='0')
  $payback_days = 00; 
  $save_lamp_cost_current = $_REQUEST['save_lamp_cost_current'];
  $ele_cost_current = $_REQUEST['ele_cost_current'];
  $relamping_current = $_REQUEST['relamping_current'];
  $total_cost3_current = $_REQUEST['total_cost3_current'];
  $air_cond_current = $_REQUEST['air_cond_current'];
  $total_save_current = $_REQUEST['total_save_current'];  
  $save_lamp_cost_solais = $_REQUEST['save_lamp_cost_solais']; 
  $ele_cost_solais = $_REQUEST['ele_cost_solais'];
  $relamping_solais = $_REQUEST['relamping_solais'];
  $total_cost3 = $_REQUEST['total_cost3'];
  $air_cond_solais = $_REQUEST['air_cond_solais'];
  $total_save_solais = $_REQUEST['total_save_solais'];
  $energy_cost = $_REQUEST['energy_cost'];
  $equi_waste = $_REQUEST['equi_waste'];
  $energy_usage = $_REQUEST['energy_usage'];
  $equi_plant = $_REQUEST['equi_plant'];
  $energy_reduction = $_REQUEST['energy_reduction'];
  $equi_cars = $_REQUEST['equi_cars'];
  $equi_gas = $_REQUEST['equi_gas']; 
  $equi_house = $_REQUEST['equi_house'];
  $product = getData($db);   
  $current_sol = $_REQUEST['current_sol'];
  $date = date("m/d/y");
  $project_name = "solais";
  $cmp_name = "solais";
  $phone = "22234165";
  $email = "solais@brainwork.net";
  $website = "http://solais.brainworkconsultants.com" ;
  

    $dirName12  = 'pdf1';
    $filename = 'test1.html';
    $handle = fopen($filename, "rb");
    while (!feof($handle)) {  
      $contents .= fread($handle, 8192); 
    }
    $contents = str_replace("__date__", "$date", $contents);
    $contents = str_replace("__project_name__", "$project_name", $contents);
    $contents = str_replace("__cmp_name__", "$cmp_name", $contents);
		$contents = str_replace("__phone__", "$phone", $contents);
		$contents = str_replace("__email__", "$email", $contents); 
    $contents = str_replace("__website__", "$website", $contents); 
    $contents = str_replace("__SITE_URL__", "$SITE_URL", $contents);
    $contents = str_replace("__c_wattage__", "$c_wattage", $contents);
    $contents = str_replace("__c_lamplife__", "$c_lamplife", $contents);
		$contents = str_replace("__c_laborcost__", "$c_laborcost", $contents);
		$contents = str_replace("__c_lampcost__", "$c_lampcost", $contents);
		$contents = str_replace("__lamp_life1__", "$lamp_life1", $contents);
		$contents = str_replace("__wattage__", "$wattage", $contents);
		$contents = str_replace("__lamp_life__", "$lamp_life", $contents);
		$contents = str_replace("__labour_cost__", "$labour_cost", $contents);
		$contents = str_replace("__c_lampcost_solais__", "$c_lampcost_solais", $contents);
		$contents = str_replace("__lamp_life_solais__", "$lamp_life_solais", $contents);
    $contents = str_replace("__num_of_lamp__", "$num_of_lamp", $contents);
    $contents = str_replace("__ele_cost__", "$ele_cost", $contents);
    $contents = str_replace("__use_hour__", "$use_hour", $contents);
    $contents = str_replace("__use_day__", "$use_day", $contents);
    $contents = str_replace("__a_period__", "$a_period", $contents);
    $contents = str_replace("__total_saving__", "$total_saving", $contents);
    $contents = str_replace("__payback_year__", "$payback_year", $contents);
    $contents = str_replace("__payback_month__", "$payback_month", $contents);
    $contents = str_replace("__payback_days__", "$payback_days", $contents);
    $contents = str_replace("__save_lamp_cost_current__", "$save_lamp_cost_current", $contents);
    $contents = str_replace("__ele_cost_current__", "$ele_cost_current", $contents);
    $contents = str_replace("__relamping_current__", "$relamping_current", $contents);
    $contents = str_replace("__total_cost3_current__", "$total_cost3_current", $contents); 
    $contents = str_replace("__air_cond_current__", "$air_cond_current", $contents);
    $contents = str_replace("__total_save_current__", "$total_save_current", $contents);
    $contents = str_replace("__save_lamp_cost_solais__", "$save_lamp_cost_solais", $contents);
    $contents = str_replace("__ele_cost_solais__", "$ele_cost_solais", $contents);
    $contents = str_replace("__relamping_solais__", "$relamping_solais", $contents);
    $contents = str_replace("__total_cost3__", "$total_cost3", $contents);
    $contents = str_replace("__air_cond_solais__", "$air_cond_solais", $contents);
    $contents = str_replace("__total_save_solais__", "$total_save_solais", $contents);
    $contents = str_replace("__energy_cost__", "$energy_cost", $contents);
    $contents = str_replace("__equi_waste__", "$equi_waste", $contents);
    $contents = str_replace("__energy_usage__", "$energy_usage", $contents);
    $contents = str_replace("__equi_plant__", "$equi_plant", $contents);
    $contents = str_replace("__energy_reduction__", "$energy_reduction", $contents);  
    $contents = str_replace("__equi_cars__", "$equi_cars", $contents);
    $contents = str_replace("__equi_gas__", "$equi_gas", $contents);
    $contents = str_replace("__equi_house__", "$equi_house", $contents);
    $contents = str_replace("__product__", "$product", $contents);
    $contents = str_replace("__current_sol__", "$current_sol", $contents);
   
   if(file_exists ($dirName12))
		  @chmod($dirName12, 0777);
		else
		  @mkdir($dirName12, 0777);
		  
     $newFile   =$dirName12."/cal_pdf_".mktime().".html";
		
     $newHandle = fopen($newFile, 'w');
     
	
    
    if (fwrite($newHandle, $contents) === FALSE) {
      $PROMPT =  "Cannot write to file ($filename)";
      $PROMPT_CLASS="error";
    }  
    fclose($handle);        
    $html_file  = "pdf1/cal_pdf_".mktime().".html";
    
    
    
    if(file_exists($html_file)){  
    $pdf=new HTML2FPDF();
    
    $pdf->AddPage();
    
    $fp = fopen($html_file,"r");
    $strContent = '';
    while (!feof($fp)) {
      $strContent .= fread($fp, 8192);
    }  
    fclose($fp);
      
      $pdf->WriteHTML($strContent,true);
      $pdf->SetFont("Arial","",8);
      $new_file_name =  "pdf1/cal_pdf_".mktime().".pdf";
      $pdf->Output($new_file_name,'F');
     
    }
       
       $dir      = "pdf1/";
   //    $file     = $DOCUMENT_ROOT."/pdf/$new_file_name";exit;
       $file     = "$DOCUMENT_ROOT$MAP_VROOT_PATH/pdf/$new_file_name";  
       //@chmod("$file",777);
      
      $url="$SITE_URL/download.php?f=$file";
      echo $url; 
function getData($db)
{
  $product_id  = $_REQUEST['product']; 
  $select = "select * from products where id = '$product_id'" ;  
  $db->query($select); 
  if($db->num_rows())
  { 
    $rec = $db->fetch_assoc();
    $name = $rec['name'];
  }
  return $name;
}
?>
