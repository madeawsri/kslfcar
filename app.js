function ajax_form(){
   bootbox.dialog({
        closeButton: false,
        onEscape: true,
        title: 'เพิ่มหมวดหมู่',
        message: "<input type='text' class='form-control cate_name' placeholder=' ชื่อหมวดหมู่ ' />",
        width: 200,
        height: 200,
        buttons:[{
            label: ' บันทึก ',
            callback: function(){
                console.log($('.cate_name').val());
            }
        }]
    });
    setTimeout(function(){$('.cate_name').focus();},500);
}

