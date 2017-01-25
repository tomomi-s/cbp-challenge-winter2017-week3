# Week 3 challenge

## Contents
The `data` folder that will hold all the saved data.
Make sure that it is writable by PHP in your OS.
On Mac OSX try this command (while in this folder in the terminal):
```
sudo chmod -R a+w data
```

The `lib` folder containing the library `data-functions.php` that contains all the functions necessary to manipulate saved data

## How to use
Require the file `lib/data-functions.php` in your script(s).

### get_index
```
$index = get_index();
```
Returns an array containing pairs of `item id` and `item name`.
Should be used for creating a list of items.

### get_data
```
$data = get_data($id);
```
Retrieves saved data for one item based on the given `$id` argument.
First argument is the id of the item that should be retrieved.
The returned data is an array containing pairs of `data name` and `data value`.

### insert_data
```
insert_data($data);
```
Saves the given data as a **new** item.
The argument should be an array containing pairs of `data name` and `data value`.
**For proper generating of index, make sure that `data` always contains a value with the key `'name'`.**

### update_data
```
update_data($id, $data);
```
Updates data for a specific saved item. If that item does not exists a new item is created.
First argument is the id of the item that should be updated.
The second argument should be an array containing pairs of `data name` and `data value`.

### delete_data
```
delete_data($id);
```
Deletes a saved item from the index and deletes it's saved data.
First argument is the id of the item that should be deleted.