var jPath = jServerPath + "/modules/" + jModuleName + "/db.php?site=" + jSiteName;

$('#form-validation').submit(function(e) {

    e.preventDefault();
    var request = $.ajax({
        url: jPath + "&mode=login",
        type: 'POST',
        data: $(this).serialize(),
        dataType: 'json'
    });

    request.done(function(data) {

        if (data.count >= 1) {
            window.location = jServerPath + '/' + jSiteName + '/paidscan2.ksl';
        } else {
            window.location = jServerPath + '/' + jSiteName + '/login.ksl';
        }


    });

    request.fail(function(jqXHR, textStatus) {

    });

});


$('.j_obj_sites').on('change', function() {
    window.location = jServerName + '/' + this.value;
})