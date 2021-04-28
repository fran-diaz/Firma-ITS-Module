<?php

$dataURL = $_REQUEST['dataURL'];
$data = decode($_REQUEST['d']);
$type = $_REQUEST['type'];


if( isset( $data['table'] ) ) {
    $nombre_tabla = ucfirst( trim( str_replace('_',' ', $data['table'] ) ) );
    $id = $data['id'];
    if( $id === $data['name']){
        $active_directory = '/'.$nombre_tabla.'/'.$data['name'];
    } else {
        $active_directory = '/'.$nombre_tabla.'/'.$data['name']." ($id)";
    }
    
    $dir_name = $nombre_tabla.' - '.$data['name'];
    $config['file_data'] = $data;
} elseif( isset( $data['report'] ) ) {
    $active_directory = '/'.$data['report'];
    $dir_name = $data['report'];
    $config['file_data'] = $data;
} else {
    $active_directory = '/Indefinido';
    $dir_name = 'Indefinido';
    $config['file_data'] = $data;
}


//$data_uri = "data:image/png;base64,iVBORw0K...";
$encoded_image = explode(",", $dataURL)[1];
$encoded_image = str_replace(' ','+',$encoded_image);
$decoded_image = base64_decode($encoded_image);
$filepath = '/home/app/public_html/resources/dropbox-files/BRAIN-APP'.$active_directory."/firma-$type.png";
file_put_contents($filepath, $decoded_image);