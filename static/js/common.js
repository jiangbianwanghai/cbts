// JavaScript Document
//复选框全选
$(function() {
    $('#checkedAll').click(function(){
        $('input[name="chk_list_id[]"]').attr("checked",this.checked);
    });
});