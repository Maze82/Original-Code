function getProduct(id)
{
  
  var count     = parent.parent.$('#tblCount').val(); 
  var lastid    = parent.parent.$('#lastid').val();  
 
    
 
	count = eval(count);  
	nCount = count+1;
  count_lastid = eval(lastid);  
	nCount_lastid = count_lastid+1;// alert(nCount_lastid);  
  var dataString = 'id='+id+'&nCount='+nCount+'&nCount_lastid='+nCount_lastid; 

  $.ajax({    
              url: "ajax_getproduct.php?"+dataString,             
              async: false,
              cache: false,
              beforeSend: function(){
          		$('#ajax_loading').show();  
          	  },
              success: function(html){  
			        	    var trTag = document.createElement("tr");
                    trTag.id = "tr"+nCount_lastid;
                    trTag.innerHTML = html;
                    parent.parent.$('#pro_mssg').hide();
                    parent.parent.document.getElementById('add_rows_div').appendChild(trTag);
                    parent.parent.document.getElementById('tblCount').value=nCount;
                    parent.parent.document.getElementById('lastid').value=nCount_lastid;
                   
                    parent.parent.Show_hide_table();
                  
  
                    
               }                         
        }); 
                           
}

function delete_qproducts(id)
{             
    if(confirm('Sure to delete?')){      
	$('#tr'+id).remove().slideUp("slow"); 
  
  $('#tblCount').val(parseInt($('#tblCount').val())-1);
    
   Show_hide_table();
  
	}
}

function delete_ward_products(id)
{             
 if(confirm('Sure to delete?'))
  {  
		var dataString ='id='+id;  
		$.ajax(
    {
			url: "ajax_delete_ward_products.php?"+dataString,
			data: dataString,
			async: false,
			cache: false,
			success: function(html)
        {     
				      if(parseInt(html)==1)
              
                {
                  $('#prd_'+id).remove().slideUp("slow");
                   $('#tblCount').val(parseInt($('#tblCount').val())-1);
                 
                }
               
			 }
		});
	}
}
function get_ward_products(val)
{ 
  
 dataString = 'val='+escape(val);
$.ajax({
    url: "/ajax_get_ward_products.php",
    data: dataString,
    async: false,
    cache: false,
    beforeSend: function()
    {   
    },
    success: function(html){  //alert(html);
	 obj = eval('(' + html + ')');
      $('#products').html(obj.products);
      $("#ordering_person").val(obj.ordering_person);
	  $("#email").val(obj.email);
     
      }   
}); 
}
function getContents()
{   
  $('#search_result').innerHTML = "Just a second..."

  
    var customer           =   $('#customer').val();
	var costcentre           =   $('#costcentre').val();
	var products           =   $('#product').val();
    var orderdate1         =   $('#orderdate1').val();
	var orderdate2            =   $('#orderdate2').val();
	var filleddate1         =   $('#filleddate1').val();
	var filleddate2            =   $('#filleddate2').val();
	var status            =   $('#status').val();
	var customertype     =$('#type').val();
     
    
  var dataString = "orderdate1="+orderdate1+"&orderdate2="+orderdate2+"&filleddate1="+filleddate1+"&filleddate2="+filleddate2+"&status="+status+"&customer="+customer+"&products="+products+"&costcentre="+costcentre+"&type="+customertype; 
    // alert("__SITE_URL__/ajax_searchCustomer.php?"+dataString);
   // alert(dataString);
   $.ajax({
              url: "/ajax_searchOrder.php?"+dataString,             
              async: false,
              cache: false,
              success: function(html){  //alert(html);
              $('#search_result').html(html);   
			       
              
               }                          
        });                                  
  
}
function addFilterPreference()
{
	
 var customer1           =   $('#customer').val();
	var costcentre1           =   $('#costcentre').val();
	var products1           =   $('#product').val();
    var orderdate11         =   $('#orderdate1').val();
	var orderdate21            =   $('#orderdate2').val();
	var filleddate11         =   $('#filleddate1').val();
	var filleddate21            =   $('#filleddate2').val();
	var status1            =   $('#status').val();
	var customertype1     =$('#type').val();
     
    
  var dataString = "orderdate11="+orderdate11+"&orderdate21="+orderdate21+"&filleddate11="+filleddate11+"&filleddate21="+filleddate21+"&status1="+status1+"&customer1="+customer1+"&products1="+products1+"&costcentre1="+costcentre1+"&type1="+customertype1; 
    // alert("__SITE_URL__/ajax_searchCustomer.php?"+dataString);
   //alert(dataString);
   $.ajax({
              url: "/ajax_savefilterreference.php?"+dataString,             
              async: false,
              cache: false,
              success: function(html){  //alert(html);
            
			       
              
               }                          
        });                             	
}

function getCustomers()
{   //alert("hi");
  $('#search_result').innerHTML = "Just a second..."

  
    var customer           =   $('#customer').val();
	var from               =   $('#from').val();
    var to                 =   $('#to').val();
	
     
    
  var dataString = "from="+from+'&to='+to+'&customer='+customer; 
     // alert("__SITE_URL__/ajax_searchCustomer.php?"+dataString);
    //alert(dataString);
   $.ajax({
              url: "/ajax_searchCustomer.php?"+dataString,             
              async: false,
              cache: false,
              success: function(html){  //alert(html);
              $('#search_result').html(html);   
			       
              
               }                          
        });                                  
  
}
function getCostcenter()
{  // alert("hi");
  $('#search_result').innerHTML = "Just a second..."

  
    var ward           =   $('#ward').val();
	var from               =   $('#from').val();
    var to                 =   $('#to').val();
	var category           =   $('#category').val();
	
     
    
  var dataString = "from="+from+'&to='+to+'&ward='+ward+'&category='+category; 
     // alert("__SITE_URL__/ajax_searchCustomer.php?"+dataString);
    //alert(dataString);
   $.ajax({
              url: "/ajax_searchCostcenter.php?"+dataString,             
              async: false,
              cache: false,
              success: function(html){  //alert(html);
              $('#search_result').html(html);   
			       
              
               }                          
        });                                  
  
}
function generate_pdf()
{   
    
    var customer           =   $('#customer').val();
	var products           =   $('#product').val();
    var orderdate1         =   $('#orderdate1').val();
	var orderdate2            =   $('#orderdate2').val();
	 var filleddate1         =   $('#filleddate1').val();
	var filleddate2            =   $('#filleddate2').val();
	var status            =   $('#status').val();
     
    
  var dataString = "orderdate1="+orderdate1+'&orderdate2='+orderdate2+'&filleddate1='+filleddate1+'&filleddate2='+filleddate2+'&status='+status+'&customer='+customer+'&products='+products; 
    
   $.ajax({
              url: "/epdf/quotePDF.php?"+dataString,             
              async: false,
              cache: false

        });                                  
  
}