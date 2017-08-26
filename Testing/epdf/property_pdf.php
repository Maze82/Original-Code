<?php

include("../config/data.config.php");
include("$LIB_DIR/class.database.php");
include("$LIB_DIR/data.constant.php");
include("$LIB_DIR/functions.library.php");
include("$LIB_DIR/html2fpdf.php");


$db=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db->open() or die($db->error());

/*$db1=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db1->open() or die($db1->error());

$db2=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db2->open() or die($db2->error());


$s_unit=$_REQUEST['s_unit']?$_REQUEST['s_unit']:'';
$s_currency=$_REQUEST['s_currency']?$_REQUEST['s_currency']:'';
$id=$_GET['id'];

   global $headline,$typename,$price,$currency,$size,$unit,$town,$region,$prefacture,$muncipality,$bedroom,$bathroom,
          $kitchen,$story,$view,$waterfront,$garage,$other,$residenc_fet1,$description,$DOCUMENT_ROOT,$refernce,$otheroption;
 
    $sql="select a.*,v.p_vname,r.r_name,p.pr_name,m.m_name,ps.p_sname,c.c_name from tbl_property as a,tbl_region as r,tbl_prefacture as p,tbl_municipality as m,
          tbl_propertysubtype as ps,tbl_currency as c,tbl_pview as v
          where p_id='$id' and a.p_view=v.p_vid and a.p_rid=r.r_id and a.p_preid=p.pr_id and a.p_mid=m.m_id and a.p_psubtype=ps.p_sid and a.p_currency=c.c_id";
    $res=$db->query($sql);
    $rows=$db->fetch_array($res);
    
    $headline=$rows['p_headline'];
    $refernce=$rows['p_propertyno'];
    $typename=$rows['p_sname'];
    
    //----------------------- currency conversion---------------------------------
          if($s_currency==3)
          {
           $sql3="select * from tbl_currency where c_id=3";
           $res3=$db1->query($sql3);
           $rows3=$db1->fetch_array($res3);
           $currency="£";
           
           if($rows['p_currency']==1)
           {
            $price1=floor($rows['p_price']/$rows3['c_conversion3']);
           }
           elseif($rows['p_currency']==2)
           {
             $price1=floor($rows['p_price']/$rows3['c_conversion1']);
           }
           else
           {
             $price1=$rows['p_price'];
           }
           
          }
          elseif($s_currency==2)
          {
           $sql3="select * from tbl_currency where c_id=2";
           $res3=$db1->query($sql3);
           $rows3=$db1->fetch_array($res3);
           $currency="$";
           
           if($rows['p_currency']==1)
           {
            $price1=floor($rows['p_price']/$rows3['c_conversion3']);
           }
           elseif($rows['p_currency']==3)
           {
             $price1=floor($rows['p_price']/$rows3['c_conversion2']);
           }
           else
           {
             $price1=$rows['p_price'];
           }
            
          }
          else
          {
            $sql3="select * from tbl_currency where c_id=1";
           $res3=$db1->query($sql3);
           $rows3=$db1->fetch_array($res3);
           $currency="€";
           
           if($rows['p_currency']==3)
           {
            $price1=floor($rows['p_price']/$rows3['c_conversion2']);
           }
           elseif($rows['p_currency']==2)
           {
             $price1=floor($rows['p_price']/$rows3['c_conversion1']);
           }
           else
           {
             $price1=$rows['p_price'];
           }
            
          }
          
       if(strlen($price1)==4 or strlen($price1)==5 or strlen($price1)==6)
             {
               $price3=substr($price1,-3);
               $price4=substr($price1,0,-3);
               $price=$price4.".".$price3;
             }
        elseif(strlen($price1)==7 or strlen($price1)==8 or strlen($price1)==9)
          {
            $price3=substr($price1,-6,3);
            $price4=substr($price1,-3);
            $price5=substr($price1,0,-6);
            $price=$price5.".".$price3.".".$price4;
          }
         else
          {
            $price=$price1;
          }
    
    //$currency=html_entity_decode($rows['c_name']);
    
    //----------------------- size conversion ---------------------------------
    if($s_unit=='2')
            {
              $unit='Acres';
              $sql11="select * from tbl_unit where u_id='2'";
              $res11=$db1->query($sql11);
              $rows11=$db1->fetch_array($res11);
              
              if($rows['p_punit']=='1')
               {
                 $size1=floor($rows['p_psize']/$rows11['u_conversion1']);
               }
              elseif($rows['p_punit']=='5')
               {
                 $size1=floor($rows['p_psize']/$rows11['u_conversion5']);
               }
              elseif($rows['p_punit']=='4')
               {
                 $size1=floor($rows['p_psize']/$rows11['u_conversion4']);
               }
              elseif($rows['p_punit']=='3')
               {
                 $size1=floor($rows['p_psize']/$rows11['u_conversion3']);
               }
              else
               {
                 $size1=$rows['p_psize'];
               }
               
            }
           elseif($s_unit=='1')
            {
              $unit='Stremmata';
              $sql11="select * from tbl_unit where u_id='1'";
              $res11=$db1->query($sql11);
              $rows11=$db1->fetch_array($res11);
              
              if($rows['p_punit']=='2')
               {
                 $size1=floor($rows['p_psize']/$rows11['u_conversion2']);
               }
              elseif($rows['p_punit']=='5')
               {
                 $size1=floor($rows['p_psize']/$rows11['u_conversion5']);
               }
              elseif($rows['p_punit']=='4')
               {
                 $size1=floor($rows['p_psize']/$rows11['u_conversion4']);
               }
              elseif($rows['p_punit']=='3')
               {
                 $size1=floor($rows['p_psize']/$rows11['u_conversion3']);
               }
              else
               {
                 $size1=$rows['p_psize'];
               }
            }
           elseif($s_unit=='5')
            {
              $unit="ft<sup>2</sup>";
              $sql11="select * from tbl_unit where u_id='5'";
              $res11=$db1->query($sql11);
              $rows11=$db1->fetch_array($res11);
              
              if($rows['p_punit']=='2')
               {
                 $size1=floor($rows['p_psize']/$rows11['u_conversion2']);
               }
              elseif($rows['p_punit']=='1')
               {
                 $size1=floor($rows['p_psize']/$rows11['u_conversion1']);
               }
              elseif($rows['p_punit']=='4')
               {
                 $size1=floor($rows['p_psize']/$rows11['u_conversion4']);
               }
              elseif($rows['p_punit']=='3')
               {
                 $size1=floor($rows['p_psize']/$rows11['u_conversion3']);
               }
              else
               {
                 $size1=$rows['p_psize'];
               }
            }
           elseif($s_unit=='3')
            {
              $unit="Hectares";
              $sql11="select * from tbl_unit where u_id='3'";
              $res11=$db1->query($sql11);
              $rows11=$db1->fetch_array($res11);
              
              if($rows['p_punit']=='2')
               {
                 $size1=floor($rows['p_psize']/$rows11['u_conversion2']);
               }
              elseif($rows['p_punit']=='1')
               {
                 $size1=floor($rows['p_psize']/$rows11['u_conversion1']);
               }
              elseif($rows['p_punit']=='5')
               {
                 $size1=floor($rows['p_psize']/$rows11['u_conversion5']);
               }
              elseif($rows['p_punit']=='4')
               {
                 $size1=floor($rows['p_psize']/$rows11['u_conversion4']);
               }
              else
               {
                 $size1=$rows['p_psize'];
               }
            }
           else
            {
             $unit="m<sup>2</sup>";
              $sql11="select * from tbl_unit where u_id='4'";
              $res11=$db1->query($sql11);
              $rows11=$db1->fetch_array($res11);
              
             if($rows['p_punit']=='2')
               {
                 $size1=floor($rows['p_psize']/$rows11['u_conversion2']);
               }
              elseif($rows['p_punit']=='1')
               {
                 $size1=floor($rows['p_psize']/$rows11['u_conversion1']);
               }
              elseif($rows['p_punit']=='5')
               {
                 $size1=floor($rows['p_psize']/$rows11['u_conversion5']);
               }
              elseif($rows['p_punit']=='3')
               {
                 $size1=floor($rows['p_psize']/$rows11['u_conversion3']);
               }
              else
               {
                 $size1=$rows['p_psize'];
               }
            }
     if(strlen($size1)==4 or strlen($size1)==5 or strlen($size1)==6)
     {
       $size3=substr($size1,-3);
       $size4=substr($size1,0,-3);
       $size=$size4.".".$size3;
     }
    elseif(strlen($size1)==7 or strlen($size1)==8 or strlen($size1)==9)
    {
      $size3=substr($size1,-6,3);
      $size4=substr($size1,-3);
      $size5=substr($size1,0,-6);
      $size=$size5.".".$size3.".".$size4;
    }
    else
     {
       $size=$size1;
     }
     
     global $countview;
  
     $sql4="select p_viewscount from tbl_property where p_id='$id'";
     $res4=$db->query($sql4);
     $rows4=$db->fetch_array($res4);
     $countview=$rows4['p_viewscount'];
     
    //$unit=$rows['p_punit'];
    $town=$rows['p_city'];
    $region=$rows['r_name'];
    $prefacture=$rows['pr_name'];
    $muncipality=$rows['m_name'];
    $bedroom=$rows['p_bedroom'];
    $bathroom=$rows['p_bathroom'];
    $kitchen=$rows['p_kitchen'];
    $story=$rows['p_story'];
    $view=$rows['p_vname'];
    $con_year=$rows['p_conyear'];
    $laundry=$rows['p_laundry'];
    //$waterfront=$rows['p_wname'];
    $garage=$rows['p_garage'];
    
    $other1=$rows['p_other'];
    $other=explode(",",$other1);
    $b=1;
    for($i=0;$i<count($other);$i++)
     {
       $otheroption.="<img src='pdf/spacer-new.jpg' width='10' height='10' />-&nbsp;".$other[$i]."<br>";
     }
    
      //$description=substr($rows['p_description'],0,315)."...";
      $description1=$rows['p_description'];
      $description1=strip_tags($description1);
      $description1=htmlentities(substr($description1,0,280));
      if($description1!="")
      {
        $description="<tr>
                        <td colspan='4' bgcolor='#ebeaea'><div class='headingClassFull'>Description</div></td>
                      </tr>  
                        <tr>
                        <td colspan='4' bgcolor='#2f2d2d'><img src='images/spacer.jpg' width='1' height='1' /></td>
                      </tr>
                      <tr>
                        <td colspan='4' style='font-family:Verdana, Arial, Helvetica, sans-serif; font-size:10px; color:#000000; padding:6px; line-height:12px;'>
                         $description1
                        </td>
                      </tr>";
      
      }
      else
      {
        $description="";
      }
      //$description1=substr($description,0,80);
      //$description2=substr($description,81,160);
      //$description3=substr($description,161,250);
      //$description=$description1."<br>".$description2."<br>".$description3;
    
    //---------------------- comercial features----------------------------------------
       if($rows['p_ptype']==1)
       {
         if($laundry>0)
          {
           $wc="<tr>
                  <td  class='blackText' style='padding:5px;'># of W/C's:</td>
                  <td align='right' class='blueText' style='padding:5px;border-right:1px solid #000000'><span class='blueText' style='padding-right:7px;'>$laundry</span></td>
                </tr>";
          }
         $residenc_fet="<tr>
                        <td  class='blackText' style='padding:5px;'>Construction Year:</td>
                        <td align='right'  class='blueText' style='padding:5px;border-right:1px solid #000000'><span class='blueText' style='padding-right:7px;'>$con_year</span></td>
                        <td  class='blackText' style='padding:5px;' colspan='2' rowspan='5' valign='top'>
                          $otheroption
                        </td>
                      </tr>
                      <tr>
                        <td  class='blackText' style='padding:5px;'># of Bedrooms:</td>
                        <td align='right' class='blueText' style='padding:5px;border-right:1px solid #000000'><span class='blueText' style='padding-right:7px;'>$bedroom</span></td>
                      </tr>
                      <tr>
                        <td  class='blackText' style='padding:5px;'># of Bathrooms:</td>
                        <td align='right' class='blueText' style='padding:5px;border-right:1px solid #000000'><span class='blueText' style='padding-right:7px;'>$bathroom</span></td>
                      </tr>
                       $wc
                      <tr>
                        <td  class='blackText' style='padding:5px;'># of Kitchens:</td>
                        <td align='right' class='blueText' style='padding:5px;border-right:1px solid #000000'><span class='blueText' style='padding-right:7px;'>$kitchen</span></td>
                      </tr>
                      <tr>
                        <td  class='blackText' style='padding:5px;'># of Storeys:</td>
                        <td align='right' class='blueText' style='padding:5px;border-right:1px solid #000000'><span class='blueText' style='padding-right:7px;'>$story</span></td>
                      </tr>";
         
       }
       else
       {
         $residenc_fet="";
         $residenc_fet1="";
       }
    
      if($rows['p_ptype']==1 or $rows['p_ptype']==3)
       {
         $garage_fet="<tr>
                      <td  class='blackText' style='padding:5px;' valign='top'>Garage :</td>
                      <td align='right' class='blueText' style='padding:5px;border-right:1px solid #000000'><span class='blueText' style='padding-right:7px;'>$garage</span></td>
                      </tr>";
       }
      else
       {
         $garage_fet="";
       }
      
    
    
    //----------------------- for image ----------------------------
     global $imagename,$threeimagename;
   
       $sql5="select * from tbl_pimage where i_pid='$id' limit 0,3";
       $res=$db2->query($sql5);
       $threeimagename.="<td align='center' valign='top'><img src='pdf/spacer-new.jpg' width='27' height='118' />";
       while($rows5=$db2->fetch_array())
        {
          $pdf_image=$rows5['thumb_image'];
          $img_ext=getExtension($pdf_image);
          if($img_ext='jpg' or $img_ext='jpeg')
          $threeimagename.="<img src='pro_images/".$rows5['thumb_image']."' width='185' height='115'  /><img src='pdf/spacer-new.jpg' width='37' height='115' />";
          else
          $threeimagename.="";
        }
         $threeimagename.="</td>";
    //------------------------------- for Agent info-----------------------------
      global $fname,$lname,$name,$phone,$a_image,$l_image,$fax,$website,$agency,$email;
 
    $sql3="select user_id from tbl_property where p_id='$id'";
    $res=$db->query($sql3);
    $rows3=$db->fetch_array($res);
    $userid=$rows3['user_id'];
  
    $sql1="select a_type,a_email from tbl_account where a_id='$userid'";
    $res1=$db1->query($sql1);
    $rows1=$db1->fetch_array($res1);
    $type=$rows1['a_type'];
    $email1=$rows1['a_email'];
    if($email1!="")
     {
       $email=$rows1['a_email'];
     }
    else
     {
       $email="webmaster@greekpropertyexchange.com";
     }
    if($type==1)
     {
       $sql2="select * from tbl_jaccount where a_jaid='$userid'";
       $res2=$db1->query($sql2);
       $rows2=$db1->fetch_array($res2);
       $fname=$rows2['a_jfname'];
       $lname=$rows2['a_jlname'];
       $agency1=$rows2['a_jagency'];
       
       if($fname!="" or $lname!="")
        {
          $name="<span style='color:#000000;'>Name:&nbsp;&nbsp;<span style='color: rgb(0, 0, 255);'>$fname&nbsp;$lname</span><br>";
        }
       else
        {
          $name="";
        }
       if($rows2['a_jagency']=="")
        {
         $agency="";
        }
       else
        {
          $agency="<span style='color:#000000;'>Agency:&nbsp;&nbsp;<span style='color: rgb(0, 0, 255);'>$agency1</span><br>";
        }
       $website1=$rows2['a_jwebsite'];
       if($rows2['a_jwebsite']=="")
        {
          $website="";
        }
       else
        {
          $website="<span style='color:#000000;'>Website:&nbsp;&nbsp;<a style='text-decoration: none;'  href='$website1' target='_blank'>click here</a>";
        }
       $fax1=$rows2['a_jfax'];
       if($rows2['a_jfax']=="")
        {
          $fax="";
        }
       else
        {
          $fax="<span style='color:#000000;'>Fax:&nbsp;&nbsp;<span style='color: rgb(0, 0, 255);'>$fax1</span><br>";
        }
       $phone1=$rows2['a_jphone'];
        if($rows2['a_jphone']=="")
         {
           $phone="";
         }
        else
         {
           $phone="<span style='color:#000000;'>Telephone:&nbsp;&nbsp;<span style='color: rgb(0, 0, 255);'> $phone1</span><br>";
         }
         
       $agencyimage=$rows2['a_jproimage'];
       if($agencyimage!="")
        {
         $a_image="<img src='pro_images/$agencyimage' width='65' height='82'  >";
        }
       else
        {
         $a_image="";
        }
        
        $logoimage=$rows2['a_jlogo'];
        if($logoimage!="")
        {
         $l_image="<img src='pro_images/$logoimage' width='65' height='50'>";
        }
       else
        {
         $l_image="";
        }
     }
    elseif($type==2)
     {
       $sql2="select * from tbl_iaccount where a_iaid='$userid'";
       $res2=$db1->query($sql2);
       $rows2=$db1->fetch_array($res2);
       $fname=$rows2['a_ifname'];
       $lname=$rows2['a_ilname'];
       $agency1=$rows2['a_iagency'];
       if($fname!="" or $lname!="")
        {
          $name="<span style='color:#000000;'>Name:&nbsp;&nbsp;<span style='color: rgb(0, 0, 255);'>$fname&nbsp;$lname</span><br>";
        }
       else
        {
          $name="";
        }
       if($rows2['a_iagency']=="")
        {
         $agency="";
        }
       else
        {
          $agency="<span style='color:#000000;'>Agency:&nbsp;&nbsp;<span style='color: rgb(0, 0, 255);'>$agency1</span><br>";
        }
       $website1=$rows2['a_iwebsite'];
       if($rows2['a_iwebsite']=="")
        {
          $website="";
        }
       else
        {
          $website="<span style='color:#000000;'>Website:&nbsp;&nbsp;<a style='text-decoration: none;'  href='$website1' target='_blank'>click here</a>";
        }
       $fax1=$rows2['a_ifax'];
       if($rows2['a_ifax']=="")
        {
          $fax="";
        }
       else
        {
          $fax="<span style='color:#000000;'>Fax:&nbsp;&nbsp;<span style='color: rgb(0, 0, 255);'>$fax1</span><br>";
        }
       $phone1=$rows2['a_iphone'];
        if($rows2['a_iphone']=="")
         {
           $phone="";
         }
        else
         {
           $phone="<span style='color:#000000;'>Telephone:&nbsp;&nbsp;<span style='color: rgb(0, 0, 255);'> $phone1</span><br>";
         }
         
       $agencyimage=$rows2['a_iproimage'];
       if($agencyimage!="")
        {
         $a_image="<img src='pro_images/$agencyimage' width='65' height='82'  >";
        }
       else
        {
         $a_image="";
        }
        
        $logoimage=$rows2['a_ilogo'];
        if($logoimage!="")
        {
         $l_image="<img src='pro_images/$logoimage' width='65' height='50' >";
        }
       else
        {
         $l_image="";
        }
     }
    else
     {
       $sql2="select * from tbl_paccount where a_paid='$userid'";
       $res2=$db1->query($sql2);
       $rows2=$db1->fetch_array($res2);
       $fname=$rows2['a_pfname'];
       $lname=$rows2['a_plname'];
       $agency1=$rows2['a_pagencyname'];
       if($fname!="" or $lname!="")
        {
          $name="<span style='color:#000000;'>Name:&nbsp;&nbsp;<span style='color: rgb(0, 0, 255);'>$fname&nbsp;$lname</span><br>";
        }
       else
        {
          $name="";
        }
       if($rows2['a_pagencyname']=="")
        {
         $agency="";
        }
       else
        {
          $agency="<span style='color:#000000;'>Agency:&nbsp;&nbsp;</span><span style='color: rgb(0, 0, 255);'>$agency1</span><br>";
        }
       $website1=$rows2['a_pwebsite'];
       if($rows2['a_pwebsite']=="")
        {
          $website="";
        }
       else
        {
          $website="<span style='color:#000000;'>Website:&nbsp;&nbsp;<a style='text-decoration: none;' href='$website1' target='_blank'>click here</a>";
        }
       $fax1=$rows2['a_pfax'];
       if($rows2['a_pfax']=="")
        {
          $fax="";
        }
       else
        {
          $fax="<span style='color:#000000;'>Fax:&nbsp;&nbsp;<span style='color: rgb(0, 0, 255);'>$fax1</span><br>";
        }
       $phone1=$rows2['a_pphone'];
        if($rows2['a_pphone']=="")
         {
           $phone="";
         }
        else
         {
           $phone="<span style='color:#000000;'>Telephone:&nbsp;&nbsp;<span style='color: rgb(0, 0, 255);'> $phone1</span><br>";
         }
         
       $agencyimage=$rows2['a_pproimage'];
       if($agencyimage!="")
        {
         $a_image="<img src='pro_images/$agencyimage' width='65' height='82'  >";
        }
       else
        {
         $a_image="";
        }
        
        $logoimage=$rows2['a_plogo'];
        if($logoimage!="")
        {
         $l_image="<img src='pro_images/$logoimage' width='65' height='50'>";
        }
       else
        {
         $l_image="";
        }
     }     */
    
    $dirName      = 'pdf';
  
    $filename = 'test1.html';
    $handle = fopen($filename, "rb");
    while (!feof($handle)) {
      $contents .= fread($handle, 8192);
    }
    
    if(file_exists ($dirName))
		  @chmod($dirName, 0777);
		else
		  @mkdir($dirName, 0777);
		  
     $newFile   =$dirName."/test12.html";
		 //$newfile="http://www.greekpropertyexchange.com/pdf/header.jpg";
     $newHandle = fopen($newFile, 'w');

   /* $contents = str_replace("__headline__", "$headline", $contents);
		$contents = str_replace("__countview__", "$countview", $contents);
		$contents = str_replace("__refernce__", "$refernce", $contents);
		$contents = str_replace("__typename__", "$typename", $contents);
		$contents = str_replace("__currency__", "$currency", $contents);
		$contents = str_replace("__price__", "$price", $contents);
		$contents = str_replace("__size__", "$size", $contents);
		$contents = str_replace("__unit__", "$unit", $contents);
		$contents = str_replace("__town__", "$town", $contents);
		$contents = str_replace("__muncipality__", "$muncipality", $contents);
    $contents = str_replace("__prefacture__", "$prefacture", $contents);
    $contents = str_replace("__region__", "$region", $contents);
    $contents = str_replace("__bedroom__", "$bedroom", $contents);
    $contents = str_replace("__bathroom__", "$bathroom", $contents);
    $contents = str_replace("__otheroption__", "$otheroption", $contents);
    $contents = str_replace("__description__", "$description", $contents);
    $contents = str_replace("__fname__", "$fname", $contents);
    $contents = str_replace("__lname__", "$lname", $contents);
    $contents = str_replace("__name__", "$name", $contents);
    $contents = str_replace("__a_image__", "$a_image", $contents);
    $contents = str_replace("__l_image__", "$l_image", $contents);
    $contents = str_replace("__agency__", "$agency", $contents);
    $contents = str_replace("__phone__", "$phone", $contents);
    $contents = str_replace("__fax__", "$fax", $contents);
    $contents = str_replace("__email__", "$email", $contents);
    $contents = str_replace("__website__", "$website", $contents);
    $contents = str_replace("__residenc_fet__", "$residenc_fet", $contents);
    $contents = str_replace("__residenc_fet1__", "$residenc_fet1", $contents);
    $contents = str_replace("__garage_fet__", "$garage_fet", $contents);
    $contents = str_replace("__threeimagename__", "$threeimagename", $contents);
    $contents = str_replace("__view__", "$view", $contents);
    //$contents = str_replace("__waterfront__", "$waterfront", $contents);   */
   $contents = str_replace("__hi__", "hi", $contents); 
   if (fwrite($newHandle, $contents) === FALSE) {
      $PROMPT =  "Cannot write to file ($filename)";
      $PROMPT_CLASS="error";
    }
    fclose($handle);
    

    $html_file  = "pdf/test12.html";
    //$pdf_file   = "pdf/property_pdf.pdf";
   //echo $contents;
   // exit;
     
    if(file_exists($html_file)){
    $pdf=new HTML2FPDF();
    //$pdf=new PDF();
    $pdf->AddPage();
    //$pdf->Header();
    $fp = fopen($html_file,"r");
    $strContent = '';   
    while (!feof($fp)) {
      $strContent .= fread($fp, 8192);
    }   
    fclose($fp);
    
      $pdf->WriteHTML($strContent,true);
      $pdf->Output("pdf/test12.pdf",'F');
     
    }
      
       $dir      = "pdf/";
       $file     = $DOCUMENT_ROOT."/pdf/pdf/test12.pdf";
       //@chmod("$file",777);
        
       //@header("location:pdf/property_pdf.pdf");
       echo"<Script language=javascript>
            <!--
             window.location='pdf/test12.pdf';
             //-->
            </Script>
             ";

?>
