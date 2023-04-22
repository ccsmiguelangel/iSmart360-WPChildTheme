jQuery(document).ready(function(){
 
  $("#group_select").change(function(){

   console.log($(this).val());

   let id = $(this).val();

   if(id=="30075"){
     $("#owner_email_field").css("display","block");
     $("#owner_textbox_field").css("display","block");
     $("#owner_day_field").css("display","block");
     $("#owner_month_field").css("display","block");
     $("#owner_year_field").css("display","block");
     $("#owner_day").css("display","block");
     $("#owner_month").css("display","block");
     $("#owner_year").css("display","block");
     $("#owner_check_field").css("display","block");
   }else{
     if(id=="30076"){
       $("#owner_email_field").css("display","none");
       $("#owner_textbox_field").css("display","none");
       $("#owner_day").css("display","block");
       $("#owner_month").css("display","block");
       $("#owner_year").css("display","block");
       $("#owner_day_field").css("display","block");
       $("#owner_month_field").css("display","block");
       $("#owner_year_field").css("display","block");
       $("#owner_check_field").css("display","none");
     }else{
       $("#owner_email_field").css("display","none");
       $("#owner_textbox_field").css("display","none");
       $("#owner_day_field").css("display","none");
       $("#owner_month_field").css("display","none");
       $("#owner_year_field").css("display","none");
       $("#owner_day").css("display","none");
       $("#owner_month").css("display","none");
       $("#owner_year").css("display","none");
       $("#owner_check_field").css("display","none");
     }
   }

 });

});
