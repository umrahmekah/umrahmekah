<?php

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=' . $title . '-data.' . strtotime(date('d-m-Y')) . '.csv');

// create a file pointer connected to the output stream
    $output = fopen('php://output', 'w');

    $head = [];
    foreach ($fields as $f) {
        if ('1' == $f['download']) {
            $limited = isset($field['limited']) ? $field['limited'] : '';
            if (\SiteHelpers::filterColumn($limited)) {
                $head[] = Lang::get('core.' . strtolower(str_replace(' ', '', preg_replace('/[^\p{L}\p{N}\s]/u', '', $f['label']))));
            }
        }
    }

    fputcsv($output, $head);

// fetch the data

    foreach ($rows as $row) {
        $content = [];
        foreach ($fields as $f) {
            if ('1' == $f['download']):
                $limited = isset($field['limited']) ? $field['limited'] : '';
            if (SiteHelpers::filterColumn($limited)) {
                $content[] = SiteHelpers::formatRows($row->{$f['field']}, $f, $row);
            }

            endif;
        }

        //echo '<pre>';print_r($content);echo '</pre>';
        fputcsv($output, $content);
        //fputcsv($fp, $content);
    }
//fclose($file);
/*
// output headers so that the file is downloaded rather than displayed
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename='.$title.' '.date("d/m/Y").'.csv');
// create a file pointer connected to the output stream
$fp = fopen('php://output', 'w');
// loop over the rows, outputting them
*/
/*
foreach ($rows as $row)
{
    $content= array();
    foreach($fields as $f )
    {
        if($f['download'] =='1'):
            $limited = isset($field['limited']) ? $field['limited'] :'';
            if(SiteHelpers::filterColumn($limited ))
            {
                $content .= '<td> '. SiteHelpers::formatRows($row->{$f['field']},$f,$row) . '</td>';
            }

        endif;
    }
    //fputcsv($fp, $content);

}
return $content;
//fclose($fp);
//exit;

?>
