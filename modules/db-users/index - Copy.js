
!function (document, window, $) {
    "use strict";
    var jPath = jServerPath+"/modules/"+jModuleName+"/db.php?site="+jSiteName;
    
    /*---- Select2 Zone Ket ----*/
    jSelect2Data('.select2-zoneket',jPath+"&mode=zoneket");
    


    
    $('.btn-find').click(function(){
       
       var zoneket = $('.select2-zoneket').val();
       zoneket = zoneket.replace('-XXX','');
       alert(zoneket);
       //var ret = jView('รายงานการต่อสัญญาชาวไร่',jPath,'find',{});
       //jLog(ret);
    });

/*
var myList = [
  { "ลำดับ": "abc", "age": 50 },
  { "ลำดับ": "25", "hobby": "swimming" },
  { "ลำดับ": "xxxx", "hobby": "programming" }
];
jBuildTable('#excelDataTable',myList);
*/
}(document, window, jQuery);


//table style
function rowStyle(row, index) {
    var classes = ['active', 'success', 'info', 'warning', 'danger'];
    if (index % 2 === 0 && index / 2 < classes.length) {
        return {
            classes: classes[index / 2]
        };
    }
    return {};
}

//table format

function nameFormatter(value, row) {
    var icon = row.id % 2 === 0 ? 'icon_star' : 'icon_star_alt'

    return '<i class="icon ' + icon + '"></i> ' + value;
}

//large columns
var $table = $('#large_tbl');
$(function () {
    buildTable($table, 15, 15);
    CORE_TEMP.function.initPerfectScroll($table.parent('div'));
});
function buildTable($el, cells, rows) {
    var i, j, row,
        columns = [],
        data = [];
    for (i = 0; i < cells; i++) {
        columns.push({
            field: 'field' + i,
            title: 'Cell' + i,
            sortable: true
        });
    }
    for (i = 0; i < rows; i++) {
        row = {};
        for (j = 0; j < cells; j++) {
            row['field' + j] = 'Row-' + i + '-' + j;
        }
        data.push(row);
    }
    $el.bootstrapTable('destroy').bootstrapTable({
        columns: columns,
        data: data
    });
}

//disabled checkbox
function idFormatter(value, row, index) {
    if (index === 2) {
        return {
            disabled: true
        };
    }
    if (index === 5) {
        return {
            disabled: true,
            checked: true
        }
    }
    if (index === 7) {
        return {
            disabled: true
        }
    }
    return value;
}

//custom toolbar
var $table1 = $('#table1'),
    $ok = $('#ok');
$(function () {
    $ok.click(function () {
        $table1.bootstrapTable('refresh');
    });
});
function queryParams() {
    var params = {};
    $('#toolbar').find('input[name]').each(function () {
        params[$(this).attr('name')] = $(this).val();
    });
    return params;
}
function responseHandler(res) {
    return res.rows;
}

//detail view
function detailFormatter(index, row) {
    var html = [];
    $.each(row, function (key, value) {
        html.push('<p><b>' + key + ':</b> ' + value + '</p>');
    });
    return html.join('');
}

//sub-table
var $subtable = $('#table-sub');

$(function () {
    buildsubTable($subtable, 5, 1);
});

function expandsubTable($detail, cells) {
    buildsubTable($detail.html('<table></table>').find('table'), cells, 1);
}

function buildsubTable($el, cells, rows) {
    var i, j, row,
        columns = [],
        data = [];

    for (i = 0; i < cells; i++) {
        columns.push({
            field: 'field' + i,
            title: 'Cell' + i,
            sortable: true
        });
    }
    for (i = 0; i < rows; i++) {
        row = {};
        for (j = 0; j < cells; j++) {
            row['field' + j] = 'Row-' + i + '-' + j;
        }
        data.push(row);
    }
    $el.bootstrapTable({
        columns: columns,
        data: data,
        iconsPrefix: "fa",
        icons: {
            detailOpen: 'fa-plus fa-plus',
            detailClose: 'fa-minus fa-minus'
        },
        detailView: cells > 1,
        onExpandRow: function (index, row, $detail) {
            expandsubTable($detail, cells - 1);
        }
    });
}

//model table
var $tablemodel = $('#tablemodel');
$(function () {
    $('#modalTable').on('shown.bs.modal', function () {
        $tablemodel.bootstrapTable('resetView');
    });
});

//icon change
$('[data-icons-prefix="fa"]').each(function () {
    var $this = $(this),
        $defaults = {
            'icons': {
                paginationSwitchDown: 'glyphicon-collapse-down icon-chevron-down',
                paginationSwitchUp: 'glyphicon-collapse-up icon-chevron-up',
                refresh: 'fa-refresh icon-refresh',
                toggle: 'fa-building-o icon-list-alt',
                columns: 'fa-bars icon-th',
                detailOpen: 'fa-plus fa-plus',
                detailClose: 'fa-minus fa-minus'
            }
        };
    $this.bootstrapTable($defaults);

});

// $(window).on('load',function(){
//     var replaced = '<div class="th-inner "><label class="icr-label"><span class="icr-item type_checkbox"></span><span class="icr-hidden"><input class="icr-input " type="checkbox" name="inline" value="on"></span></label><span class="icr-text"></span></div>';
//     $(".bs-checkbox").html(replaced);
// });

$('[data-plugin="bootstraptable"]').each(function () {
    var $this = $(this);
    $this.bootstrapTable({
        url: $this.data('url'),
    });
    //CORE_TEMP.function.initPerfectScroll($this.parent('div'));
});



$(".search").keyup(function () {
    var searchTerm = $(".search").val();
    var listItem = $('.results tbody').children('tr');
    var searchSplit = searchTerm.replace(/ /g, "'):containsi('")
    
  $.extend($.expr[':'], {'containsi': function(elem, i, match, array){
        return (elem.textContent || elem.innerText || '').toLowerCase().indexOf((match[3] || "").toLowerCase()) >= 0;
    }
  });
    
  $(".results tbody tr").not(":containsi('" + searchSplit + "')").each(function(e){
    $(this).attr('visible','false');
  });

  $(".results tbody tr:containsi('" + searchSplit + "')").each(function(e){
    $(this).attr('visible','true');
  });

  var jobCount = $('.results tbody tr[visible="true"]').length;
    $('.counter').text(jobCount + ' item');

  if(jobCount == '0') {$('.no-result').show();}
    else {$('.no-result').hide();}
		  });
      
$('.customer-table').DataTable();