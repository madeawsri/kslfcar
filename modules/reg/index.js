var jPath = jServerPath + "/modules/" + jModuleName + "/db.php?site=" + jSiteName;
var jPathElsx = jServerPath + "/modules/" + jModuleName + "/xlsx.php?site=" + jSiteName;
var jPathJson = jServerPath + "/libs/jsonDb.php?site=" + jSiteName;
var jPathCard = jServerPath + "/modules/" + jModuleName + "/card.php?site=" + jSiteName;
var jPathCardRp = jServerPath + "/modules/" + jModuleName + "/rp.php?site=" + jSiteName;

/*
jConfirm('Are you sure?').then(function(ok) {
  if (ok) {
    jLog("OK");
  }
});*/


var jPathMain = jPath;
! function(document, window, $) {
  "use strict";
  /*---- Select2 Zone Ket ----*/
  showData();
}(document, window, jQuery);
var table;

var timezoneToUse = 'Asia/Bangkok';
flatpickr('input[name="daterange"]', {
  mode: "range",
  dateFormat: "d-m-Y",
});

function showData(frmOject) {
  if (frmOject)
    var param = frmOject.serialize();
  table = $('#customer-table').DataTable({
    destroy: true,
    select: true,
    "order": [
      [0, "asc"]
    ],
    "pageLength": 8,
    "lengthChange": false,
    "pagingType": "full_numbers",
    "oLanguage": {
      "oPaginate": {
        "sFirst": "<", // This is the link to the first page
        "sPrevious": "«", // This is the link to the previous page
        "sNext": "»", // This is the link to the next page
        "sLast": ">" // This is the link to the last page
      }
    },
    "ajax": {
      "url": jPathMain + "&mode=datatable&" + param,
      "type": "POST",
      "complete": function(json) {
        ret_data = json.responseJSON;
        jLog(json.responseJSON);
      }
    }
  });

  $('#customer-table tbody').on('click', 'tr', function() {

    if ($('.selected').length < 200) {
      $(this).toggleClass('selected');
    } else if ($(this).hasClass('selected'))
      $(this).toggleClass('selected');

    var iPrint = table.rows('.selected').data().length;
    $('.badge-success').text(iPrint);

  });

  $('#customer-table tbody').on('click', 'tr', function(e) {
    e.preventDefault();
  });

  $("#searchbox").keyup(function() {
    table.search(this.value).draw();
  });



}

$('.btn-rpcard').click(function() {
  jLog("rp-card");


  var dtime = $('.date').val();
  dtime = dtime.split(" to ");

  $.ajax({
    type: 'POST',
    url: encodeURI(jPathCardRp + "&dtime=" + dtime.join('|')),
    dataType: 'json',
    cache: false,
    beforeSend: function() {
      $(".loading").show();
    },
    success: function(ret) {
      jLog(ret);
      $(".loading").hide();

      if (ret.has_data) {
        const winx = window.open("", "_blank");
        let html = '';
        html += '<html>';
        html += '<body style="margin:0!important">';
        html += '<embed width="100%" height="100%" src="data:application/pdf;base64,' + ret.data + '" type="application/pdf" />';
        html += '</body>';
        html += '</html>';

        setTimeout(() => {
          winx.document.write(html);
        }, 0);

        $('.badge-success').text(0);
        table.ajax.reload();
      } else {
        jAlert(" ไม่พบข้อมูล! ");
      }
    }
  });



});


$('.btn-print').click(function() {

  var iPrint = table.rows('.selected').data().length;
  if (!iPrint) {
    /// select datea range
    var dtime = $('.date').val();
    dtime = dtime.split(" to ");

    $.ajax({
      type: 'POST',
      url: encodeURI(jPathCard + "&dtime=" + dtime.join('|')),
      dataType: 'json',
      cache: false,
      beforeSend: function() {
        $(".loading").show();
      },
      success: function(ret) {
        jLog(ret);
        $(".loading").hide();
        if (ret.has_data) {
          const win = window.open("", "_blank");
          let html = '';
          html += '<html>';
          html += '<body style="margin:0!important">';
          html += '<embed width="100%" height="100%" src="data:application/pdf;base64,' + ret.data + '" type="application/pdf" />';
          html += '</body>';
          html += '</html>';

          setTimeout(() => {
            win.document.write(html);
          }, 0);
          $('.badge-success').text(0);
          table.ajax.reload();

        } else {
          jAlert(" ไม่พบข้อมูล! ");
        }


      }

    });

  } else {

    var dataRow = [];
    var ids = $.map(table.rows('.selected').data(), function(item) {
      dataRow.push(item[0]);
    });

    $.ajax({
      type: 'POST',
      url: encodeURI(jPathCard + "&idkey=" + dataRow.join('|')),
      dataType: 'json',
      cache: false,
      beforeSend: function() {
        $(".loading").show();
      },
      success: function(ret) {
        jLog(ret);
        $(".loading").hide();

        const win = window.open("", "_blank");
        let html = '';
        html += '<html>';
        html += '<body style="margin:0!important">';
        html += '<embed width="100%" height="100%" src="data:application/pdf;base64,' + ret.data + '" type="application/pdf" />';
        html += '</body>';
        html += '</html>';

        setTimeout(() => {
          win.document.write(html);
        }, 0);
        $('.badge-success').text(0);
        table.ajax.reload();
      }

    });

  }


});