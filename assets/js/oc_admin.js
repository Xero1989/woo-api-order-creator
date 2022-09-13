var temp;

jQuery(document).ready(function () {
  
});


function oc_save_settings() {
  
  var action = "oc_save_settings";  
  let oc_enable = jQuery("#oc_enable").prop("checked");
  let oc_description = jQuery("#oc_description").val();
  let oc_webshop_id = jQuery("#oc_webshop_id").val();  

  jQuery(".bt_save_settings").attr("disabled", true);
  jQuery(".bt_save_settings").val("Sending Data...");

  jQuery.ajax({
    url: url_admin_ajax,
    type: "post",
    data: {
      action,
      oc_enable,      
      oc_description,      
      oc_webshop_id,            
    },
    success: function (server_response) {
      server_response = JSON.parse(server_response);      

      jQuery(".bt_save_settings").attr("disabled", false);
      jQuery(".bt_save_settings").val("Save Settings");
      
      show_message(server_response, "ajax_response_message");
    },
    error: () => {
      console.log("Something went wrong");
      jQuery(".bt_save_settings").attr("disabled", false);
      jQuery(".bt_save_settings").val("Save Settings");

      let server_response = {
        "status":"error",
        "message":"Something went wrong..."
      }
      show_message(server_response, "ajax_response_message");
    }
  });
}

