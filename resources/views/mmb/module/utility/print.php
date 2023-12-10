<div class="box box-primary">
	<div class="box-header with-border"> </div>
	<div class="box-body">
	
<?php
    $content = '';
    $content .= '<table class="table table-striped table-bordered">';
    $content .= '<tr>';
    foreach ($fields as $f) {
        if ('1' == $f['download']) {
            $limited = isset($field['limited']) ? $field['limited'] : '';
            if (\SiteHelpers::filterColumn($limited)) {
                $content .= '<th style="background:#f9f9f9;">' . Lang::get('core.' . strtolower(str_replace(' ', '', preg_replace('/[^\p{L}\p{N}\s]/u', '', $f['label'])))) . '</th>';
            }
        }
    }
    $content .= '</tr>';

    foreach ($rows as $row) {
        $content .= '<tr>';
        foreach ($fields as $f) {
            if ('1' == $f['download']):
                $limited = isset($field['limited']) ? $field['limited'] : '';
            if (SiteHelpers::filterColumn($limited)) {
                $content .= '<td> ' . SiteHelpers::formatRows($row->{$f['field']}, $f, $row) . '</td>';
            }
            endif;
        }
        $content .= '</tr>';
    }
    $content .= '</table>';

    echo $content;
//	exit;

?>
	</div>
</div>



