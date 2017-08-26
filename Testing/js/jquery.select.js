function checkAll(chk, frm) {
        $('#prompt').removeClass('notice');
        $('#prompt').html('');
          with(frm)
         {  
          totalElements = elements.length;
          for(i = 0; i < totalElements; i++)
          { if (elements[i].type == 'checkbox')
           { elements[i].checked = chk;
           }
          }  
         }
        }
         
        function changeCheckAll(chk, frm) {
        $('#prompt').removeClass('notice');
        $('#prompt').html('');
          var flg;
          with(frm) {
            if(chk){
              flg=true;
              totalElements = elements.length;
            for(i = 0; i < totalElements; i++) {
               if (elements[i].type == 'checkbox' && elements[i].name != 'chkAll' && !(elements[i].checked)) {
                 flg=false;
                 break;
             }
            }
              chkAll.checked=flg;  
            } else {
              chkAll.checked=false;
            }  
         }
      }

        
        
        
        function check_unCheckAll(frmobj,status){
        $('#prompt').removeClass('notice');
        $('#prompt').html('');
        
            with(frmobj){                
                var totalElements = elements.length;
                for(i = 0; i < totalElements; i++){
                    if (elements[i].type == "checkbox"){
                        elements[i].checked = status;
                        
                    }
                }
            }
        }
        function unCheckAll(frmobj){
        $('#prompt').removeClass('notice');
        $('#prompt').html('');
        
            with(frmobj){                
                var totalElements = elements.length;
                for(i = 0; i < totalElements; i++){
                    if (elements[i].type == "checkbox"){
                        elements[i].checked = false;
                        
                    }
                }
            }
        }
        function checkSelected(frmobj)
        {
        $('#prompt').removeClass('notice');
        $('#prompt').html('');	
        with(frmobj)
            {	totalChecked = 0;
                totalElements = elements.length;
                for(i = 0; i < totalElements; i++)
                {
                    if (elements[i].type == 'checkbox')
                    {	if (elements[i].checked)
                        {	totalChecked++;
                            break;
                        }
                    }
                }
                if (totalChecked == 0)
                {	
                    $('#prompt').addClass('notice');
                    $('#prompt').html('Select atleast one record');
                    return false;
                }
                return true;
            }
        }
        function confirmDelete(frmobj)
        {	with(frmobj)
            {	if (checkSelected(frmobj))
                {	return confirm("The selected records will be deleted. Are you sure?");
                }
                else
                {	return false;
                }
            }
        }
        function confirmStatusChange(frmobj)
        {	with(frmobj)
            {	if (checkSelected(frmobj))
                {	return confirm("Changing Status for the selected Records(s).\n\nSure?");
                }
                else
                {	return false;
                }
            }
        }
        function confirmFeatureChange(frmobj)
        {	with(frmobj)
            {	if (checkSelected(frmobj))
                {	return confirm("Changing featured for the selected Records(s).\n\nSure?");
                }
                else
                {	return false;
                }
            }
        }
 function confirmDeleteOne()
 {
       return confirm("The selected records will be deleted. Are you sure?");
 }
// JavaScript Document
 function initMenus() {
	$('ul.menu ul').hide();
	
	$.each($('ul.menu'), function(){
		$('#' + this.id + '.expandfirst ul:first').show();
	});
	$('ul.menu li a').click(
		function() {
			var checkElement = $(this).next();
			var parent = this.parentNode.parentNode.id;

			if($('#' + parent).hasClass('noaccordion')) {
				$(this).next().slideToggle('normal');
				return false;
			}
			if((checkElement.is('ul')) && (checkElement.is(':visible'))) {
				if($('#' + parent).hasClass('collapsible')) {
					$('#' + parent + ' ul:visible').slideUp('normal');
				}
				return false;
			}
			if((checkElement.is('ul')) && (!checkElement.is(':visible'))) {
				$('#' + parent + ' ul:visible').slideUp('normal');
				checkElement.slideDown('normal');
				return false;
			}
		}
	);
}
