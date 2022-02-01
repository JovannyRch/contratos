<?php

header('Access-Control-Allow-Origin: *');


function save($file, $filename = NULL, $directory = NULL, $chmod = 0755)
{
    // Load file data from FILES if not passed as array
    $file = is_array($file) ? $file : $_FILES[$file];
    if ($filename === NULL) {
        // Use the default filename, with a timestamp pre-pended
        $filename = time() . $file['name'];
    }
    // Remove spaces from the filename
    $filename = preg_replace('/\\s+/', '_', $filename);
    if ($directory === NULL) {
        // Use the pre-configured upload directory
        $directory = 'uploads/';
    }
    // Make sure the directory ends with a slash
    $directory = rtrim($directory, '/') . '/';

    if (!is_dir($directory)) {
        // Create the upload directory
        mkdir($directory, 0777, TRUE);
    }
    if (!is_writable($directory)) {
        echo "File is not writable";
        return "";
    }
    if (is_uploaded_file($file['tmp_name']) and move_uploaded_file($file['tmp_name'], $filename = $directory . $filename)) {
        if ($chmod !== FALSE) {
            // Set permissions on filename
            chmod($filename, $chmod);
        }
        //$all_file_name = array(FILE_INFO => $filename);
        // Return new file path
        return $filename;
    }
    return FALSE;
}

if (isset($_FILES['file'])) {
    $file = $_FILES['file'];
    $ruta  = save($file);
    $resp = array();
    if ($ruta == FALSE || $ruta == "") {
        $resp = array('ruta' => null, 'msg' => 'Error');
    } else {
        $resp = array('ruta' => $ruta);
    }

    echo json_encode($resp);
} else {
    $resp = array('ruta' => null, 'msg' => 'Error');
    echo json_encode($resp);
}
