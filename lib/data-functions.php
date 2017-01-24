<?php

define('DATA_DIR', realpath(dirname(__FILE__).'/../data'));

$_data_index = null;

function load_index()
{
    global $_data_index;

    if($_data_index===null)
    {
        $_data_index = array();
        if(file_exists(DATA_DIR.'/index.csv'))
        {
            $fh = fopen(DATA_DIR.'/index.csv', 'r');
            while($row = fgetcsv($fh, 0, ';', '"'))
            {
                $_data_index[$row[0]] = $row[1];
            }
            fclose($fh);
        }
    }
}

function save_index()
{
    load_index();

    global $_data_index;

    $fh = fopen(DATA_DIR.'/index.csv', 'w');

    foreach($_data_index as $id => $name)
    {
        fputcsv($fh, array($id, $name), ';', '"');
    }
    fclose($fh);
}

function get_index()
{
    load_index();

    global $_data_index;

    return $_data_index;
}

function get_data_filepath($id)
{
    return DATA_DIR.'/'.str_pad(floor($id / 1000), 4, STR_PAD_LEFT).'/'.$id.'.json';
}

function get_data($id)
{
    $file = get_data_filepath($id);

    $data = array();
    if(file_exists($file))
    {
        $data = json_decode(file_get_contents($file));
    }

    return $data;
}

function update_data($id = null, $data = array())
{
    load_index();

    global $_data_index;

    if(!$id)
    {
        if(count($_data_index))
        {
            $id = max(array_keys($_data_index))+1;
        }
        else
        {
            $id = 1;
        }
    }

    $name = isset($data['name']) ? $data['name'] : '';
    
    $_data_index[$id] = $name;

    save_index();

    $file = get_data_filepath($id);

    if(!file_exists(dirname($file)))
    {
        mkdir(dirname($file), 0777, true);
    }

    file_put_contents($file, json_encode($data));

    chmod($file, 0777);
}

function insert_data($data = array())
{
    update_data(null, $data);
}

function delete_data($id)
{
    load_index();

    global $_data_index;

    unset($_data_index[$id]);

    save_index();

    $file = get_data_filepath($id);
    
    if(file_exists($file))
    {
        unlink($file);
    }
}