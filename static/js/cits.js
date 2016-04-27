jQuery(document).ready(function() {

   "use strict";

   $(".1ajax-project").click(function(){
      project_name = $(".project_name").val();
      project_discription = $("#project_discription").val();
      if (!project_name) {
         alert('请填写绩效圈名称');
         $("#project_name").focus();
         return false;
      }
      if (!project_discription) {
         alert('请填写绩效圈简介');
         $("#project_discription").focus();
         return false;
      }
      $.ajax({
        type: "POST",
        dataType: "JSON",
        url: "/project/add_ajax",
        data: "project_name="+project_name+"&project_discription="+project_discription,
        success: function(data){
          if (data.status) {
            location.href = '/';
          } else {
            alert('fail');
          } 
        }
      });
   });

   $("#addProject").submit(function(){
      $(this).ajaxSubmit({
         type:"post",
         url: "/project/add_ajax",
         dataType: "JSON",
         beforeSubmit:validFormProject,
         success:callBackProject
      });
      return false;
   });

   //Check
  jQuery('.ckbox input').click(function(){
      var t = jQuery(this);
      if(t.is(':checked')){
          t.closest('tr').addClass('selected');
      } else {
          t.closest('tr').removeClass('selected');
      }
  });

});

//验证项目表单
function validFormProject(formData,jqForm,options){
  return $("#addProject").valid();
}

//项目表单回调函数
function callBackProject(data) {
  $("#btnSubmit-project").attr("disabled", true);
  if(data.status){
    jQuery.gritter.add({
      title: '提醒',
      text: data.message,
        class_name: 'growl-success',
        image: '/static/images/screen.png',
      sticky: false,
      time: ''
    });
    setTimeout(function(){
      location.href = window.location.href;
    }, 2000);
  } else {
    jQuery.gritter.add({
      title: '提醒',
      text: data.message,
        class_name: 'growl-danger',
        image: '/static/images/screen.png',
      sticky: false,
      time: ''
    });
    setTimeout(function(){
      location.href = window.location.href;
    }, 3000);
  }
}