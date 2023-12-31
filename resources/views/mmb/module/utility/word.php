<?php
    $content = $title;
    $content .= '<table border="1">';
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

$word_xmlns                   = "xmlns:o='urn:schemas-microsoft-com:office:office' xmlns:w='urn:schemas-microsoft-com:office:word' xmlns='http://www.w3.org/TR/REC-html40'&#8221";
    $word_xml_settings        = '<xml><w:WordDocument><w:View>Print</w:View><w:Zoom>100</w:Zoom></w:WordDocument></xml>';
    $word_landscape_style     = '@page {size:8.5in 11.0in; margin:0.5in 0.31in 0.42in 0.25in;} div.Section1{page:Section1;}';
    $word_landscape_div_start = "<div class='Section1'>";
    $word_landscape_div_end   = '</div>';
    $content                  = '
	<html ' . $word_xmlns . '>
	<head>' . $word_xml_settings . '<style type="text/css">
	' . $word_landscape_style . ' table,td {border:1px solid #f9f9f9;} </style>
	</head>
	<body>' . $word_landscape_div_start . $content . $word_landscape_div_end . '</body>
	</html>
	';

    @header('Content-Type: application/msword');
    @header('Content-Length: ' . strlen($content));
    @header('Content-disposition: inline; filename="' . $title . ' ' . date('d/m/Y') . '.doc"');
    echo $content;
        exit;
