<?php
//defined('BASEPATH') OR exit('No direct script access allowed');
//if (!$_SERVER['HTTP_REFERER'])
	//redirect($this->uri->segment(1).'/dashboard');
	?>
<?php //echo form_open_multipart(); 

  $token = md5(uniqid(rand(), TRUE));  

  if(isset ($_SESSION['form_token_doc']))
  {
  	unset($_SESSION['form_token_doc']);
  }
  $_SESSION['form_token_doc'] = $token;
  ?>

<div id="content">
  <div class="container-fluid">
    <?php 
if(isset($_SESSION['pop_mes']))
{
	popup();
}?>
    <div class="clearfix"></div>
    <?php 
if(isset($_SESSION['error_pop_mes']))
{
	error_popup();
}
?>
    <div class="clearfix"></div>
    <h1 class="lg-heading">Document Uploads</h1>
    <div class="main-content-wrap white-bg">
      <div class="upload-section paddingbottom2x">
        <div class="spacebottom2x">
          <p class="sm-text"><strong class="black spacebottom1x">Documents</strong></p>
          <p><strong class="black">Step 1/3 : Please upload one of the following documents as proof of identity.</strong></p>
          <div class="row spacetop2x spacebottom2x clearfix">
            <div class="col-md-6 col-sm-6 col-xs-12">
              <div class="doc-box">National ID</div>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <div class="doc-box">Resident identity card</div>
            </div>
          </div>
          <p class="xs-text spacebottom2x">A color copy of valid passport or other official state ID (e.g. driver's license, identity card etc).<br/>
            The ID must be valid and contain the client's full name, an issue or expiry date, the client's place and date of birth OR tax identification number and the client's signature. In the case of a driver's license, please upload both the sides.
</p>
        </div>
          <?php echo form_open_multipart('document-upload',array('method'=>'post','id'=>'uploadform1','class'=>'form-horizontal clearfix'));?>
        
          <input type="hidden" name="my_token_doc" value="<?php echo $token;?>">
          <input type="hidden" name="language" value="<?php echo $this->uri->segment(1);?>">
          <div class="row clearfix">
            <div class="col-xs-6 col-xxs">
              <div class="form-group file-group">
                <input type="file" name="file1" id="file1" class="file" >
                <div class="input-group col-xs-12"> <span class="input-group-addon"><i class="fa fa-upload" aria-hidden="true"></i></span>
                  <input type="text" class="form-control file-field" disabled placeholder="Upload file">
                  <span class="input-group-btn">
                  <button class="browse browse-btn" type="button"><i class="glyphicon glyphicon-search"></i> Browse</button>
                  </span> </div>
              </div>
            </div>
            <div class="col-xs-6 col-xxs">
              <div class="btn-box spacetop-xs text-right">
                <button type="submit" class="btn-login" id="form1sumbit">Upload Documents</button>

                
              <div class="page-loader" id="page-loader1" style="display:none;">
              <div class="cssloader">
    <div class="sh1"></div>
    <div class="sh2"></div>
    <h4 class="lt">loading</h4>
</div>
              </div>
              
              </div>
            </div>
          </div>
        </form>
      </div>
      
      
      
      <div class="upload-section paddingtop2x paddingbottom2x">
        <div class="spacebottom2x">
          <p><strong class="black">Step 2/3 : Please upload one of the following documents as proof of residency.</strong></p>
          <div class="row spacetop2x spacebottom2x clearfix">
            <div class="col-md-4 col-sm-4 col-xs-12">
              <div class="doc-box">Utility bill</div>
            </div>
            <div class="col-md-4 col-sm-4 col-xs-12">
              <div class="doc-box">Water bill</div>
            </div>
            <div class="col-md-4 col-sm-4 col-xs-12">
              <div class="doc-box">Bank statement</div>
            </div>
          </div>
          <p class="xs-text spacebottom2x">A recent utility bill (e.g. electricity, gas, water, phone, oil, internet and/or cable TV connections), or bank statement dated within the last 3 months confirming your registered address.</p>
        </div>
         <?php echo form_open_multipart('document-upload',array('method'=>'post','id'=>'uploadform2','class'=>'form-horizontal clearfix'));?>
        
          <input type="hidden" name="my_token_doc" value="<?php echo $token;?>">
            <input type="hidden" name="language" value="<?php echo $this->uri->segment(1);?>">
          <div class="row clearfix">
            <div class="col-xs-6 col-xxs">
              <div class="form-group file-group">
                <input type="file" name="file2" id="file2" class="file">
                <div class="input-group col-xs-12"> <span class="input-group-addon"><i class="fa fa-upload" aria-hidden="true"></i></span>
                  <input type="text" class="form-control file-field" disabled placeholder="Upload file">
                  <span class="input-group-btn">
                  <button class="browse browse-btn" type="button"><i class="glyphicon glyphicon-search"></i> Browse</button>
                  </span> </div>
              </div>
            </div>
            <div class="col-xs-6 col-xxs">
              <div class="btn-box spacetop-xs text-right">
                <button type="submit" id="form2sumbit" class="btn-login transitions">Upload Documents</button>

              <div class="page-loader" id="page-loader2" style="display:none;">
                      <<div class="cssloader">
    <div class="sh1"></div>
    <div class="sh2"></div>
    <h4 class="lt">loading</h4>
</div>
              </div>
              
              </div>
            </div>
          </div>
        </form>
      </div>
      
      
      
            <div class="upload-section paddingbottom2x">
        <div class="spacebottom2x">
          <p class="sm-text"><strong class="black spacebottom1x">Documents</strong></p>
          <p><strong class="black">Step 3/3 : Please upload following documents.</strong></p>
          <div class="row spacetop2x spacebottom2x clearfix">
            <div class="col-md-6 col-sm-6 col-xs-12">
              <div class="doc-box">Credit Card Front</div>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <div class="doc-box">Credit Card Back</div>
            </div>
          </div>
          <p class="xs-text spacebottom2x">Credit card front and back side.<br/>
            
</p>
        </div>
          <?php echo form_open_multipart('document-upload',array('method'=>'post','id'=>'uploadform3','class'=>'form-horizontal clearfix'));?>
        
          <input type="hidden" name="my_token_doc" value="<?php echo $token;?>">
          <input type="hidden" name="language" value="<?php echo $this->uri->segment(1);?>">
          <div class="row clearfix">
            <div class="col-xs-6 col-xxs">
              <div class="form-group file-group">
                <input type="file" name="file3" id="file3" class="file" >
                <div class="input-group col-xs-12"> <span class="input-group-addon"><i class="fa fa-upload" aria-hidden="true"></i></span>
                  <input type="text" class="form-control file-field" disabled placeholder="Upload file">
                  <span class="input-group-btn">
                  <button class="browse browse-btn" type="button"><i class="glyphicon glyphicon-search"></i> Browse</button>
                  </span> </div>
              </div>
            </div>
            <div class="col-xs-6 col-xxs">
              <div class="btn-box spacetop-xs text-right">
                <button type="submit" class="btn-login" id="form3sumbit">Upload Documents</button>

                
              <div class="page-loader" id="page-loader1" style="display:none;">
              <div class="cssloader">
    <div class="sh1"></div>
    <div class="sh2"></div>
    <h4 class="lt">loading</h4>
</div>
              </div>
              
              </div>
            </div>
          </div>
        </form>
      </div>
      
      
      
      <div class="upload-section paddingtop2x">
        <div class="spacebottom2x">
          <div class="common-table pagination-tbl">
            <table id="doc_table" class="table table-striped">
              <thead>
                <tr>
                  <th width="50%"><?php echo "Document"; ?></th>
                  <th width="50%"><?php echo "Uploaded Date"; ?></th>
                </tr>
              </thead>
              <tbody>
                <?php 
						
                         if(count($result) > 0)
								{
									foreach ($result as $row) {?>
                <tr>
                  <?php
            $path = $_SERVER['DOCUMENT_ROOT'];
            $file_url=$row->file_url;
            $get_file=str_replace($path,wp_site_url,$file_url);	
            ?>
                            <td><a onClick="downloadfile('<?php echo $row->file_name; ?>');"><?php echo wordwrap($row->original_filename,15,"<br>\n",True);?></a></td>
                  
                  <td><?php print $row->uploaded_date;?></td>
                  <?php //echo print $row->file_url;?>
                </tr>
                <?php }
								}
								else {	
								?>
                <tr>
                  <td><b> <?php echo "No account attachment records found."; ?></b></td>
                  <td></td>
                </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script>


function downloadfile(str) {

	// alert(str);
	   $.ajax({
		url : "<?= base_url($this->uri->segment(1).'/document_upload/download_attempt') ?>",
	    type : "get",
	    data : "dw_file=" + str,
	    success : function(dw) {
		
	     window.location = dw;
	    }
	   });
	  };


$(function() {

$(document).ready(function(){

	$('#doc_table').DataTable({
		  paging: true,
	    //  "lengthMenu": [[3, 5, -1], [3, 5, "All"]],
	      "bFilter":false,
	      "responsive": true,
	      "aaSorting": [[1, "desc"]],
	      "pageLength": 4,
	      "lengthChange": false,
		  "bInfo" : false,
		  "pagingType": "numbers",//"simple"
	});
	   	    
  	$.validator.addMethod( "extension", function( value, element, param ) {
  		param = typeof param === "string" ? param.replace( /,/g, "|" ) : "png|gif|jpg|JPG|JPEG|PNG|GIF|PDF|pdf";
  		return this.optional( element ) || value.match( new RegExp( "\\.(" + param + ")$", "i" ) );
  	}, $.validator.format( "Please enter a value with a valid extension." ) );

    $.validator.addMethod("Isfilesize1", function(value, element) {
		var fsizemy = $('#file1')[0].files[0].size;
		if(fsizemy<2097150)
            { 
        		return true;        		
			 }
		   else{
			   return false;
            }
        });
    
    $.validator.addMethod("Isfilesize2", function(value, element) {
		var fsizemy = $('#file2')[0].files[0].size;
		if(fsizemy<2097150)
            { 
        		return true;        		
			 }
		   else{
			   return false;
            }
        });


    $.validator.addMethod("Isfilesize3", function(value, element) {
		var fsizemy = $('#file3')[0].files[0].size;
		if(fsizemy<2097150)
            { 
        		return true;        		
			 }
		   else{
			   return false;
            }
        });
    
    
    $("#popupcloseu").click(function(){
	    $("#success-popupu").hide();

	  <?php   $updated_url = str_replace('index.php', '', $_SERVER['PHP_SELF']); ?>
	    
	    if(typeof window.history.pushState == 'function') {
	        window.history.pushState({}, "Hide", '<?php echo $updated_url.$this->uri->segment(1).'/dashboard';?>');
	    }

    });

    $("#popupclose2u").click(function(){
	    $("#error-popupu").hide();

	 <?php   $updated_url = str_replace('index.php', '', $_SERVER['PHP_SELF']); ?>
	    
	    if(typeof window.history.pushState == 'function') {
	        window.history.pushState({}, "Hide", '<?php echo $updated_url.$this->uri->segment(1).'/dashboard';?>');
	    }
	    
    });

	
    $("#uploadform2").validate({
        rules: {
       	 file2: { required: true,
           	extension:true,
           	Isfilesize2:true},

        },
        messages: {
       	 file2: {
	             required: '<?php echo lang('Please select a File.'); ?>',
	             extension:'<?php echo ('Allowed file types: png,gif,jpg,pdf'); ?>',
	             Isfilesize2:'<?php echo 'File size should be less than 2MB'; ?>',
	            },
       	},
        tooltip_options: {
           thefield: { placement: 'right' }
        },

        submitHandler: function (form) { 
    	    
            $("#form2sumbit").hide();
            $("#page-loader2").show();
            form.submit();
        }

        
     });

    $("#uploadform1").validate({
        rules: {
       	 file1: { required: true,
           	extension:true,
           	Isfilesize1:true},

        },
        messages: {
       	 file1: {
	             required: '<?php echo lang('Please select a File.'); ?>',
	             extension:'<?php echo ('Allowed file types: png,gif,jpg,pdf'); ?>',
	             Isfilesize1:'<?php echo 'File size should be less than 2MB'; ?>',
	            },
       	},
        tooltip_options: {
           thefield: { placement: 'right' }
        },

        submitHandler: function (form) { 
    	    
            $("#form1sumbit").hide();
            $("#page-loader1").show();
            form.submit();

        }

        
     });


            $("#uploadform3").validate({
                rules: {
               	 file3: { required: true,
                   	extension:true,
                   	Isfilesize3:true},

                },
                messages: {
               	 file3: {
        	             required: '<?php echo lang('Please select a File.'); ?>',
        	             extension:'<?php echo ('Allowed file types: png,gif,jpg,pdf'); ?>',
        	             Isfilesize3:'<?php echo 'File size should be less than 2MB'; ?>',
        	            },
               	},
                tooltip_options: {
                   thefield: { placement: 'right' }
                },

                submitHandler: function (form) { 
            	    
                    $("#form3sumbit").hide();
                    $("#page-loader1").show();
                    form.submit();

                }

                
             });
            

  });
});
    </script> 