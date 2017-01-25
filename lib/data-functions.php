<?php

define('DATA_DIR', realpath(dirname(__FILE__).'/../data'));

$_data_index = null;

/**
 * loads the index of all the items into global $_data_index
 *
 * loads it from the file DATA_DIR.'/index.csv'
 * should be called always before working with the index
 * if the data is already loaded it does not do anything
 * @return void
 */
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

/**
 * saves the current state of the global $_data_index
 * 
 * saves it into the file DATA_DIR.'/index.csv'
 * @return void
 */
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

/**
 * gets the current index data
 *
 * loads the data first, then returns the globacl $_data_index
 * @return array - array of the data in the item index
 */
function get_index()
{
    load_index();

    global $_data_index;

    return $_data_index;
}

/**
 * forms a proper filename for storing a data item
 *
 * it forms it based on it's id
 * @param integer $id - id for which the file path should be formed
 * @return string - the path to the file
 */
function get_data_filepath($id)
{
    return DATA_DIR.'/'.str_pad(floor($id / 1000), 4, STR_PAD_LEFT).'/'.$id.'.json';
}

/**
 * gets data for a specific item
 *
 * returns empty array if the proper data file is not found or is badly formed
 * @param integer $id - id of the data item to be retrieved
 * @return array - an associative array with the item's data or empty array on failure
 */
function get_data($id)
{
    $file = get_data_filepath($id);

    $data = array();
    if(file_exists($file))
    {
        $data = json_decode(file_get_contents($file), true);
        if($data === false) // json data badly formed
        {
            $data = array();
        }
    }

    return $data;
}

/**
 * updates data for a specific item
 *
 * if the id is empty a new item will be created with the supplied data
 * if the item does not exist in the index it adds it
 * if the data file does not exist it creates it
 * @param integer $id - id of the item to be updated or null to create a new one
 * @param array $data - an associative array containing all the data for an item
 * @return integer - the id of the updated/created item
 */
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

    $name = isset($data['name']) ? $data['name'] : '-- noname --';
    
    $_data_index[$id] = $name;

    save_index();

    $file = get_data_filepath($id);

    if(!file_exists(dirname($file)))
    {
        mkdir(dirname($file), 0777, true);
    }

    file_put_contents($file, json_encode($data));

    chmod($file, 0777);

    return $id;
}

/**
 * inserts a new item with the given data
 *
 * just a wrapper around calling update_data without an id
 * @param array $data - an associative array containing all the data for the newly
 *                      created item
 * @return integer id of the created item
 */
function insert_data($data = array())
{
    return update_data(null, $data);
}

/**
 * deletes a data item
 *
 * removes it from the index and deletes the data file
 * if such an item does not exist in the index or the data file does not exist,
 * removes only that which exists
 * @param integer $id - id of the item to be deleted
 * @return void
 */
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