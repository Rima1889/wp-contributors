jQuery(document).ready(function(){
	jQuery("input[name='team_category[]']").click(function(){
		if(jQuery(this).prop("checked") == true){
            jQuery(this).attr('checked', 'checked');
        }
        else if(jQuery(this).prop("checked") == false){
            jQuery(this).removeAttr('checked');
        }
	});
	jQuery(".team-form").validate({
		rules: {
			team_title: "required",
			team_content: "required",
			team_logo: {
				required: true,
				extension: "jpg|jpeg|png|ico|bmp"
			}
		},
		messages: {
			team_title: "Please enter the Team title.",
			team_content: "Please enter the Team content.",
			team_logo: {
				required: "Please upload file.",
				extension: "Please upload file in these format only (jpg, jpeg, png, ico, bmp)."
			}
		},
		submitHandler: function(form) {
			var form_id = jQuery('.team-form')[0]; 
			var postdata = new FormData(form_id);
			jQuery.ajax({
				url: TEAM.ajaxurl,
				type: "POST",
				async: false,
				dataType: "json",
				data: postdata,
				contentType: false,
				processData: false,
				success: function(response){
					if( response.status == 1 ){
						window.location.href = TEAM.redirecturl;
					}
				}
			});
		}
	});
});