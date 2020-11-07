<?php

/**
 * Created by PhpStorm.
 * User: boonyadol
 * Date: 28/01/2017
 * Time: 11:44 AM
 */
class tableClass
{
    public $header = '';
    private $ret = '';

    private $id = '';
    public $rows = '';
    public $ishow = '10';
    public $showFooter = 'hide';
    public $search = 'true';
    public $paging = 'true';
    public $info = 'true';
    public $scrollX = 'false';
    public $style = false;
    
    function __construct($id="",$headers=null,$rows=null,$ishow='10',$showFooter='hide',$search='true',$paging = 'true',$info='true'){
        $this->id = $id;
        $this->rows = $rows;
        $this->ishow = $ishow;
        $this->showFooter = $showFooter;
        $this->search = $search;
        $this->paging = $paging;
        $this->info = $info;
        if($headers)
          $this->header = $this->tbHeader($headers);
        else $this->header = "<tr></tr>";
        return 0;
    }
    
    function init($id,$headers=null,$rows=null,$ishow='10',$showFooter='hide',$search='true',$paging = 'true',$info='true'){
        $this->id = $id;
        $this->rows = $rows;
        $this->ishow = $ishow;
        $this->showFooter = $showFooter;
        $this->search = $search;
        $this->paging = $paging;
        $this->info = $info;
        if($headers)
          $this->header = $this->tbHeader($headers);
        else $this->header = "<tr></tr>";
        return 0;
    }
    
    function tbHeader($headers){
        $hs = '<tr>';
        if($headers)
        foreach($headers as $k=> $v){
            $hs .=  "<th width=''>{$v}</th>";
        }
        return $hs.'</tr>';
    }
    function tbHeaderFix($headers,$cr,$x=""){
        $hs = "<tr {$x}>";
        if($headers)
            foreach($headers as $k=> $v){
                $c = explode(',',$cr[$k]);
                $r = $c[1]; $c= $c[0];
                $hs .=  "<th colspan='{$c}' rowspan='{$r}' >{$v}</th>";
            }                                                                                                                                                   
        return $hs.'</tr>';
    }
    function tbRows($rows){
        $bd = '';
        $bd .=  '<tbody>';
        $trPattern = "<tr id='_tr%s' >%s</tr>";
        $rs = '';
        if($rows){
            foreach($rows as $k=>$v) {
                foreach ($v as $vv)
                    $rs .= "<td>{$vv}</td>";
                $bd .= sprintf($trPattern,$k,$rs);
                $rs = '';
            }
        }
        $bd .='</tbody>';
        return $bd;
    }
    function tbFooter($showFooter,$hs){
        $ft = '';
        $ft .= "<tfoot class='{$showFooter}'>";
        $ft .= $this->header;
        $ft .= '</tfoot>';
        $ft .= '</table>';
        return $ft;
    }
    function render($header=""){
        $this->ret .= $header;
        $this->ret .=  ' <table id="'.$this->id.'" class="table table-bordered table-striped" style="width:98%" ><thead>';
        $this->ret .= $this->header;
        $this->ret .= '</thead>';
        $this->ret .= $this->tbRows($this->rows);
        //$this->ret .= $this->tbFooter($this->showFooter,$this->header);
        
        if(!$this->style)
        $this->ret .= '
        <script>
         var  '.$this->id.' = $("#'.$this->id.'").DataTable({
            "pageLength": '.$this->ishow.',
            "paging": '.$this->paging.',
            "lengthChange": false,
            "searching":  false,
            "ordering": true,
            "info": '.$this->info.',
            "autoWidth": false,
            "aaSorting": [],
            "scrollX": '.$this->scrollX.'
           });
        </script>
        ';
        return $this->ret;
    }

    //function (){ }





}