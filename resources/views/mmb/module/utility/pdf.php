
<div style="width:760px !important; ;">
<?php

    $content = Lang::get('core.' . strtolower(str_replace(' ', '', preg_replace('/[^\p{L}\p{N}\s]/u', '', $title))));
    $content .= '<table  class="table">';
    $content .= '<tr>';
    foreach ($fields as $f) {
        if ('1' == $f['download']) {
            $content .= '<th style="background:#f9f9f9;">' . Lang::get('core.' . strtolower(str_replace(' ', '', preg_replace('/[^\p{L}\p{N}\s]/u', '', $f['label'])))) . '</th>';
        }
    }
    $content .= '</tr>';

    foreach ($rows as $row) {
        $content .= '<tr>';
        foreach ($fields as $f) {
            if ('1' == $f['download']):
                $content .= '<td> ' . SiteHelpers::formatRows($row->{$f['field']}, $f, $row) . '</td>';
            endif;
        }
        $content .= '</tr>';
    }
    $content .= '</table>';
    echo $content;
?>
</div>
<style>
body {
font-size: 15px;
color: #34495e;

  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  font-family: Arial, sans-serif;
  overflow-x: hidden;
  overflow-y: auto;
}

.table {  border: 1px solid #EBEBEB; width: 90%;}
.table   tr  th { font-size: 11px; }
.table   tr  td {
  border-top: 1px solid #e7eaec;
  line-height: 1.42857;
 
  font-size:11px;
 	
  vertical-align: top; 
}
	
</style>