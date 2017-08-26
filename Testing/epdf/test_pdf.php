<?php
include("../config/data.config.php");
include("$LIB_DIR/class.database.php");
include("$LIB_DIR/data.constant.php");
include("$LIB_DIR/functions.library.php");
include("$LIB_DIR/mpdf/mpdf.php");

$db=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db->open() or die($db->error());   
  
  global $SITE_URL,$DOCUMENT_ROOT;
  
  $c_wattage = "60";
  $c_lamplife = "2,000";
  $c_laborcost = "$ 10.00";
  $c_lampcost = "$ 5.00";
  $lamp_life1 = "0.5";
  $wattage = "18";
  $lamp_life = "35,000";   
  $labour_cost = "$ 10.00";
  $c_lampcost_solais = "$ 65.00";
  $lamp_life_solais = "8.3";
  $num_of_lamp = "100";
  $ele_cost = "$ 0.12";
  $use_hour = "12.0";
  $use_day = "350";
  $a_period = "5";
  $total_saving = "$ 17,863.33";
  $payback_year = "1";
  $payback_month = "3";
  $payback_days = "14";
  $save_lamp_cost_current = "NA";
  $ele_cost_current = "$ 30.24";
  $relamping_current = "10.5";
  $total_cost3_current = "$ 30,870.00";
  $air_cond_current = "-$ 3,528.33";
  $total_save_current = "-$ 17,863.33";  
  $save_lamp_cost_solais = "$ 30.24"; 
  $ele_cost_solais = "$ 9.07";
  $relamping_solais = "0.6";
  $total_cost3 = "$ 16,535.00";
  $air_cond_solais = "$ 3,528.33";
  $total_save_solais = "$ 17,863.33";
  $energy_cost = "$ 17,641.41";
  $equi_waste = "35.5";
  $energy_usage = "147,000";
  $equi_plant = "22.5";
  $energy_reduction = "232,689";
  $equi_cars = "20.2";
  $equi_gas = "11,872"; 
  $equi_house = "12.8";
  $product =    "LR30";
  $current_sol = "Halogen PAR";
  $date = date("m/d/y");
  $project_name = "solais";
  $cmp_name = "solais";
  $phone = "22234165";
  $email = "info@solais.com";
  $website = "http://solaislighting.com" ;  
       
$html = "<html>
<head>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />
  <title></title>
  </head>
  <body>
    <table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" style=\"font-size:12px;color:rgb(65,65,65);\" align=\"left\" border=\"0\">
    <tr>
    <td width=\"40%\" rowspan=\"7\" valign=\"top\"><img src=\"$DOCUMENT_ROOT/images/solais2.jpg\"></td>
    <td width=\"50%\" align=\"left\" nowrap=\"nowrap\" valign=\"bottom\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Date Generated:&nbsp;&nbsp;&nbsp;&nbsp;$date</td>
    </tr>
    <tr>
    
    <td width=\"50%\" align=\"left\" nowrap=\"nowrap\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Job/Project Name:&nbsp;&nbsp;&nbsp;&nbsp;$project_name</td>
    </tr>
    <tr>
    
    <td width=\"50%\" align=\"left\" nowrap=\"nowrap\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Company name:&nbsp;&nbsp;&nbsp;&nbsp;$cmp_name</td>
    </tr>
    <tr>
    <tr>
    
    <td width=\"50%\" align=\"left\" nowrap=\"nowrap\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Contact Telephone #:&nbsp;&nbsp;&nbsp;&nbsp;$phone</td>
    </tr>
    <tr>
   
    <td width=\"50%\" align=\"left\" nowrap=\"nowrap\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Contact Email:&nbsp;&nbsp;&nbsp;&nbsp;$email</td>
    </tr>
    <tr>
    
    <td width=\"50%\" align=\"left\" nowrap=\"nowrap\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Contact Website:&nbsp;&nbsp;&nbsp;&nbsp;$website</td>
    </tr>
    </table>
     <table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
      <tr>
      <td width=\"15%\" rowspan=\"2\" style=\"font-size:50px;color:rgb(85,85,85); \">RESULTS</td>
      <td width=\"85%\" height=\"25\" style=\"background:url(../images/123.gif) repeat-x 1px;color:rgb(85,85,85);\" >&nbsp;</td>
      </tr>
      <tr>
      <td valign=\"top\" style=\"padding-bottom:10px; font-size:20px;color:rgb(95,95,95);\">RETROFIT COST ANALYSIS & ENERGY SAVINGS</td>
      </tr>
      </table>
<br />
    <table  width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#ffffff\">
    <tr><td style=\"border:1px solid #C0C0C0;\" colspan=\"200\" height=\"30\"><strong>INPUT | </strong>Values based on your average usage and costs:</td></tr>
 
          
          <tr>
          <td style=\"border:1px solid #C0C0C0;\" colspan=\"200\" height=\"30\">
          <table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" style=\"font-size:12px;color:rgb(65,65,65);\">
          <tr>
          <td align=\"left\" width=\"20%\">&nbsp;</td>
          <td align=\"left\" width=\"20%\" nowrap><strong>Current Solution</strong></td>
          <td align=\"left\" width=\"18%\" nowrap><strong>Solais Solution</strong></td>
          <td align=\"right\" width=\"26%\">&nbsp;</td>
          <td align=\"right\" width=\"16%\" nowrap><strong>Additional Info</strong></td>
          </tr>
          <tr>
          <td align=\"right\" width=\"20%\">Lamp Type:&nbsp;&nbsp;&nbsp;</td>
          <td align=\"left\" width=\"20%\">$current_sol</td>
          <td align=\"left\" width=\"18%\">$product</td>
          <td align=\"right\" width=\"26%\">Number of Lamps:&nbsp;&nbsp;&nbsp;</td>
          <td align=\"right\" width=\"16%\">$num_of_lamp</td>
          </tr>
          <tr>
          <td align=\"right\" width=\"20%\">Wattage:&nbsp;&nbsp;&nbsp;</td>
          <td align=\"left\" width=\"20%\">$c_wattage</td>
          <td align=\"left\" width=\"18%\">$wattage</td>
          <td align=\"right\" width=\"26%\">Electrical Cost/kWh:&nbsp;&nbsp;&nbsp;</td>
          <td align=\"right\" width=\"16%\">$ele_cost</td>
          </tr>
          <tr>
          <td align=\"right\" width=\"20%\">Lamp Life (hours):&nbsp;&nbsp;&nbsp;</td>
          <td align=\"left\" width=\"20%\">$c_lamplife</td>
          <td align=\"left\" width=\"18%\">$lamp_life</td>
          <td align=\"right\" width=\"26%\">Average Daily Use (hours):&nbsp;&nbsp;&nbsp;</td>
          <td align=\"right\" width=\"16%\">$use_hour</td>
          </tr>
          <tr>
          <td align=\"right\" width=\"20%\">Labor Cost :&nbsp;&nbsp;&nbsp;</td>
          <td align=\"left\" width=\"20%\">$c_laborcost</td>
          <td align=\"left\" width=\"18%\">$labour_cost</td>
          <td align=\"right\" width=\"26%\">Days of Use/Year :&nbsp;&nbsp;&nbsp;</td>
          <td align=\"right\" width=\"16%\">$use_day</td>
          </tr>
          <tr>
          <td align=\"right\" width=\"20%\">Lamp Cost :&nbsp;&nbsp;&nbsp;</td>
          <td align=\"left\" width=\"20%\">$c_lampcost</td>
          <td align=\"left\" width=\"18%\">$c_lampcost_solais</td>
          <td align=\"right\" width=\"26%\">Analysis Period (years):&nbsp;&nbsp;&nbsp;</td>
          <td align=\"right\" width=\"16%\">$a_period</td>
          </tr>
          <tr>
          <td align=\"right\" width=\"20%\">Lamp Life (years):&nbsp;&nbsp;&nbsp;</td>
          <td align=\"left\" width=\"20%\">$lamp_life1</td>
          <td align=\"left\" width=\"18%\">$lamp_life_solais</td>
          <td align=\"right\" width=\"26%\">&nbsp;</td>
          <td align=\"right\" width=\"16%\">&nbsp;</td>
          </tr>
          </table>
          </td>
          </tr>
          </table>
          <br />
        <!--p><img src=\"http://solais.brainwork.com/images/arrow_down.jpg\" ></p-->
        
          <table  width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#ffffff\">
    <tr><td style=\"border:1px solid #C0C0C0;\" colspan=\"100\"><strong>OUTPUT | </strong>Cost Savings Results:</td></tr>
    
    
    
    <tr>
    <td style=\"border:1px solid #C0C0C0;\" colspan=\"100\">
     <table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" style=\"font-size:12px;color:rgb(65,65,65);\" border=\"0\">
     <!--tr>
    <tr><td colspan=\"100\">&nbsp;</td></tr-->
     <tr>
     <td colspan=\"10\">&nbsp;</td>
     <td colspan=\"83\" style=\"border:1px solid #C0C0C0;width:563px;\">Total Savings: &nbsp;&nbsp;&nbsp;<strong>$total_saving</strong>
        &nbsp;&nbsp;&nbsp;Time Until Payback:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>$payback_year</strong> Year(s)
        &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<strong>$payback_month</strong> Month(s)
        &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<strong>$payback_days</strong> Day(s)</td>
    <td colspan=\"7\">&nbsp;</td>
    </tr>
    <tr>
    <td colspan=\"100\">
    <table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\">
    <tr>
    <td style=\"width:140px;\">&nbsp;</td>
    <td><strong>Current Solution</strong>&nbsp;</td>
    <td><strong>Solais Solution</strong></td>
     <td rowspan=7 width=\"25%\"></td>
     <td></td>
    </tr>
	<tr>
    <td style=\"width:140px;\">Cost of Retrofit:</td>
    <td>$save_lamp_cost_current&nbsp;</td>
    <td>$ele_cost_current</td>
     <td rowspan=6>
     <table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" style=\"font-size:10pxcolor:rgb(65,65,65);\">
     <tr><td>(1)Annual, per lamp</td></tr>
     <tr><td>(2)Over Analysis Period</td></tr>
     <tr><td>(3)Over Analysis Period</td></tr>
     <tr><td>(4)Over Analysis Period;3W reduction<br />
     in lighting lower AC load by 1W</td></tr>
     <tr><td>(5)Over Analysis Period</td></tr>
     </table>
     </td>
    </tr>
	<tr>
    <td style=\"width:140px;\">Electrical Cost (1):</td>
    <td>$ele_cost_current&nbsp;</td>
    <td>$ele_cost_solais</td>
    
    </tr>
	<tr>
    <td style=\"width:140px;\">Relamping (2):</td>
    <td>$relamping_current&nbsp;</td>
    <td>$relamping_solais</td>
     
    </tr>
	<tr>
    <td style=\"width:140px;\">Total Cost (3):</td>
    <td>$total_cost3_current&nbsp;</td>
    <td>$total_cost3</td>
     
    </tr>
	<tr>
    <td style=\"width:140px;\">Air Cond. Savings (4):</td>
    <td>$air_cond_current&nbsp;</td>
    <td>$air_cond_solais</td>
    
    </tr>
	<tr>
    <td style=\"width:140px;\">Total Savings (5):</td>
    <td>$total_save_current&nbsp;</td>
    <td>$total_save_solais</td>
    
    </tr>
    
    </table>
    </td>
	
    </tr>
    </table>
    </td>
    
    
    

    </tr>
    
    </table>
    <br />
    <table  width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#ffffff\">
    <tr><td style=\"border:1px solid #C0C0C0;\" colspan=\"200\" height=\"30\"><strong>OUTPUT | </strong>Environmental Savings Results:</td></tr>
 
          
          <tr>
          <td style=\"border:1px solid #C0C0C0;\" colspan=\"200\" height=\"30\">
          <table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" style=\"font-size:12px;color:rgb(65,65,65);\">
          <tr>
        <td align=\"left\" width=\"10%\" nowrap=\"nowrap\" height=\"45\"  valign=\"top\">&nbsp;&nbsp;&nbsp;Energy Cost Savings:<br>&nbsp;</td>
        <td align=\"left\" width=\"10%\" height=\"50\" valign=\"top\">$energy_cost</td>
        <td align=\"left\" width=\"10%\" nowrap height=\"50\" valign=\"top\">&nbsp;&nbsp;&nbsp;Equivalent Waste Recycled(tons):</td>
        <td align=\"left\" width=\"10%\" height=\"50\" valign=\"top\">$equi_waste</td>
        </tr>
        <tr>
        <td align=\"left\" width=\"10%\" nowrap=\"nowrap\" height=\"45\" valign=\"top\">&nbsp;&nbsp;&nbsp;Energy Usage Savings(kWh):</td>
        <td align=\"left\" width=\"10%\" height=\"50\" valign=\"top\">$energy_usage</td>
        <td align=\"left\" width=\"10%\" nowrap=\"nowrap\" height=\"50\" valign=\"top\">&nbsp;&nbsp;&nbsp;Equivalent Planting of Forests(acres):</td>
        <td align=\"left\" width=\"10%\" height=\"50\" valign=\"top\">$equi_plant</td>
        </tr>
        <tr valign=\"top\">
        <td align=\"left\" width=\"10%\" nowrap=\"nowrap\" height=\"45\" valign=\"top\">&nbsp;&nbsp;&nbsp;CO2 Emissions Reduction (pounds):</td>
        <td align=\"left\" width=\"10%\" valign=\"top\">$energy_reduction</td>
        <td align=\"left\" width=\"10%\" nowrap=\"nowrap\" valign=\"top\">&nbsp;&nbsp;&nbsp;Equivalent Cars Removed from Road:</td>
        <td align=\"left\" width=\"10%\" valign=\"top\">$equi_cars</td>
        </tr>
        <tr valign=\"top\">
        <td align=\"left\" width=\"10%\" nowrap=\"nowrap\" height=\"45\" valign=\"top\">&nbsp;&nbsp;&nbsp;Equivalent Gas Saved (gallons):</td>
        <td align=\"left\" width=\"10%\" valign=\"top\">$equi_gas</td>
        <td align=\"left\" width=\"10%\" nowrap=\"nowrap\" valign=\"top\">&nbsp;&nbsp;&nbsp;Equivalent Houses Supplied with Electricity:</td>
        <td align=\"left\" width=\"10%\" valign=\"top\">$equi_house</td>
        </tr>
          </table>
          </td>
          </tr>
          </table>
          
        <p style=\"font-size:12px;\"><font color=\"#393939\">* Actual savings may vary from the estimated savings. The estimate of savings is not a warranty or representation of saving in any particular use. All environmental analysis data is taken from the US EPA website. For more information about the Solais Energy Calculator, please contact info@solais.com.
        
        <div align=\"center\" style=\"font-size:12px;\"><strong>Solais Lighting, INC.</strong> | e:info@solais.com | t: 203683.6222 | solais.com</div></font>
        </p> 
         
        </div>
  </body>
</html>";

//==============================================================
//==============================================================
//==============================================================



$mpdf=new mPDF('en-GB','A4','','',10,10,10,25,16,13); 

$mpdf->useOnlyCoreFonts = true;

$mpdf->SetDisplayMode('fullpage');
//$mpdf->SetMargins(0,0,10);

$mpdf->list_indent_first_level = 0;	// 1 or 0 - whether to indent the first level of a list

// LOAD a stylesheet
$stylesheet = file_get_contents('mpdfstyletables.css');
//$mpdf->WriteHTML($stylesheet,1);	// The parameter 1 tells that this is css/style only and no body/html/text

$mpdf->WriteHTML($html);

$mpdf->Output();
exit;



?>