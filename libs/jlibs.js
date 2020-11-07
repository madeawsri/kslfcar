$(document).on('click', '[data-toggle="excel-download"]', function() {

  var $target = $($(this).data('target'));
  var classes = $(this).data('classes');
  $target.toggleClass(classes);

  jLog($target);

  return false;
});

$(document).on('click', '[data-toggle="ss-year"]', function() {

  var $ss_year = $(this).attr('ss-year');
  var jPath = jServerPath + "/modules/allpage/db.php?site=" + jSiteName;
  $.post(jPath + "&mode=set-year&fyear=" + $ss_year, function(data) {
    $('.ss_year').html($ss_year);
    //setTimeout(function(){ $('.content').click(); },500);
    jLog(data);
  });

  return false;
});



(function($) {
  $.fn.serializeFormJSON = function() {

    var o = {};
    var a = this.serializeArray();
    $.each(a, function() {
      if (o[this.name]) {
        if (!o[this.name].push) {
          o[this.name] = [o[this.name]];
        }
        o[this.name].push(this.value || '');
      } else {
        o[this.name] = this.value || '';
      }
    });
    return o;
  };
})(jQuery);


function jLog(data) {
  console.log(data);
}

function jAlert(msg) {
  //  swal(msg);
  //bootbox.alert("This is the default alert!");
  swal(
    msg
  );
}



function jView(p_title, p_url, p_mode, p_param) {
  var ret = null;
  var data = {
    "mode": p_mode
  };
  data = $.extend(data, p_param);
  swal({
    title: p_title,
    html: $('<div>').addClass('font-th').text('กำลังโหลดข้อมูล...'),
    onOpen: function() {
      //swal.showLoading();
      swal({
        title: 'Please Wait..!',
        text: 'Is working..',
        allowOutsideClick: false,
        allowEscapeKey: false,
        allowEnterKey: false,
        onOpen: () => {
          swal.showLoading()
        }
      })
      $.ajax({
        async: false,
        type: "POST",
        dataType: "json",
        url: p_url, //Relative or absolute path to response.php file
        data: data,
        success: function(data) {
          swal.close();
          ret = data;
        }
      });
    }
  });
  return ret;
}

async function jAjax(p_url, p_mode, p_param) {
  let _data = null;
  let _error = null;
  var data = {
    "mode": p_mode
  };
  data = $.extend(data, p_param);
  return await $.ajax({
    //async: false,
    type: "POST",
    dataType: "json",
    url: p_url,
    data: data,
    success: function(data) {
      _data = data;
    },
    error: function(request, status, error) {
      _error = (request.responseText);
    }
  });
}
/*
function jGetValue(p_url, p_mode, p_param) {
  
  var data = {
    "mode": p_mode
  };
  data = $.extend(data, p_param);

  var ret = (function () {
    
    var result;
    $.ajax({
      type: 'POST',
      url: p_url,
      dataType: 'json',
      async: false,
      data: data,
      success: function (datax) {
        result = datax;
      }
    });
    return result;
  })();

  jLog(ret);

}*/

function jValue(p_url, p_mode, p_param) {
  var ret = null;
  var data = {
    "mode": p_mode
  };
  data = $.extend(data, p_param);
  swal({
    html: $('<div>').addClass('font-th').text('กำลังโหลดข้อมูล...'),
    onOpen: function() {
      //swal.showLoading();
      swal({
        title: 'Please Wait..!',
        text: 'Is working..',
        allowOutsideClick: false,
        allowEscapeKey: false,
        allowEnterKey: false,
        onOpen: () => {
          swal.showLoading()
        }
      })
      $.ajax({
        async: false,
        type: "POST",
        dataType: "json",
        url: p_url, //Relative or absolute path to response.php file
        data: data,
        success: function(data) {
          swal.close();
          ret = data;
        }
      });
    }
  });
  return ret;
}



function jInitTable(obj) {
  //-- init table
  var table = obj.DataTable({
    destroy: true,
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
    }
  });

  $("#searchbox").keyup(function() {
    table.search(this.value).draw();
  });

}





// Builds the HTML Table out of myList.
function jBuildTable(selector, myList, hideHead) {
  if (hideHead === undefined) {
    hideHead = true;
  }
  var columns = addAllColumnHeaders(myList, selector, hideHead);
  for (var i = 0; i < myList.length; i++) {
    var row$ = $('<tr/>');
    for (var colIndex = 0; colIndex < columns.length; colIndex++) {
      var cellValue = myList[i][columns[colIndex]];
      if (cellValue == null) cellValue = "";
      row$.append($('<td/>').html(cellValue));
    }
    $(selector).append(row$);
  }
}
// Adds a header row to the table and returns the set of columns.
// Need to do union of keys from all records as some records may not contain
// all records.
function addAllColumnHeaders(myList, selector, hideHead) {
  var columnSet = [];
  var headerTr$ = $('<tr/>');

  for (var i = 0; i < myList.length; i++) {
    var rowHash = myList[i];
    for (var key in rowHash) {
      if ($.inArray(key, columnSet) == -1) {
        columnSet.push(key);
        if (hideHead === true)
          headerTr$.append($('<th/>').html(key));
      }
    }
  }
  if (hideHead === true)
    $(selector).append($('<thead/>').append(headerTr$));

  return columnSet;
}



function jSelect2Data(objName, path, p_param) {
  var param = {
    ajax: {
      url: path,
      dataType: 'json',
      delay: 250,
      data: function(params) {
        return {
          q: params.term, // search term
        };
      },
      processResults: function(data, page) {
        jLog(data);
        return {
          results: data.items
        };
      }
    }
  };
  /*
  width: '150px',
         placeholder: p_placeholder,
         allowClear: true,
  */
  param = $.extend(param, p_param);
  jLog(param);
  $(objName).select2(param);
}

function isBlank(data) {
  return ($.trim(data).length == 0);
}

function jPost(p_url, p_mode, p_param) {
  var jret = null;
  $.ajax({
    async: false,
    type: 'POST',
    url: encodeURI(p_url + "&mode=" + p_mode),
    data: p_param,
    dataType: 'json',
    cache: false,
    beforeSend: function() {
      $(".loading").show();
    },
    success: function(ret) {
      jret = ret;
      $(".loading").hide();
    }
  });
  return jret;
}

/***** new project Q-KSL */

function jSelect2Data_new(obj, title, jServerPath, jmode, objParent) {
  //jLog(jServerPath);
  return obj.select2({
    allowClear: true,
    placeholder: title,
    width: '100%',
    dropdownParent: objParent,
    ajax: {
      url: jServerPath + '&mode=' + jmode,
      dataType: 'json',
      delay: 250,
      processResults: function(data) {
        return {
          results: data
        };
      },
      cache: true
    }
  });
}

function jSelect2Data_tags(obj, title, jServerPath, jmode, objParent) {
  jLog(jServerPath);
  obj.select2({
    tags: true,
    allowClear: true,
    placeholder: title,
    width: '100%',
    dropdownParent: objParent,
    ajax: {
      url: jServerPath + '&mode=' + jmode,
      dataType: 'json',
      delay: 250,
      processResults: function(data) {
        return {
          results: data
        };
      }
    }
  });
}

function jSelect2NoJson_multi(objModal, txtIDSelect) {
 return  objModal.find('#' + txtIDSelect).select2({
    allowClear: true,
    placeholder: ' กรุณาเลือกข้อมูล ',
    multiple:"multiple",
    width: '100%',
    dropdownParent: objModal,
    tokenSeparators: [','],
  }); //.trigger('change');

}

function jSelect2NoJson(txtParectIDModal, txtIDSelect) {
  $('#' + txtParectIDModal).find('#' + txtIDSelect).select2({
    allowClear: true,
    placeholder: ' กรุณาเลือกข้อมูล ',
    width: '100%',
    dropdownParent: $('#' + txtParectIDModal),
    val: '',
    title: function() {
      return $(this).prev().attr("title");
    },
    placement: "auto",
  }); //.trigger('change');

}

async function jConfirmInfo(p_param) {
  var param = {
    type: 'info',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
  };
  param = $.extend(param, p_param);
  return swal(param).catch(swal.noop);
}


async function jAlertWarning(p_param) {
  var param = {
    position: 'top-end',
    type: 'warning',
    //title: 'Your work has been saved',
    //showConfirmButton: false,
    //timer: 1500
  };
  param = $.extend(param, p_param);
  return swal(param).catch(swal.noop);
}

async function jConfirmDelete(p_param) {
  var param = {
    //title: 'Are you sure?',
    //text: msg,
    type: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'ลบข้อมูล',
    cancelButtonText: 'ยกเลิก'

  };
  param = $.extend(param, p_param);
  return swal(param).catch(swal.noop);
}

var is_value = function(obj) {
  return !((obj == null) || (typeof obj == 'undefined') || obj == '' );
}

/*
function resolveAfter2Seconds() {
  return new Promise(resolve => {
    setTimeout(() => {
      resolve('resolved');
    }, 2000);
  });
}
async function asyncCall() {
  console.log('calling');
  var result = await resolveAfter2Seconds();
  console.log(result);
  // expected output: 'resolved'
}
asyncCall();
*/