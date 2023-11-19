# Laravel Vue3 Table

## Introduction
`laravel-vue3-table` is an advanced table component for Laravel using Vue.js 3. It enables the creation of dynamic, interactive tables with easy integration with Laravel Eloquent ORM.

## Features
- **Dynamic Data Loading**: Easy fetching of data from the server using Laravel Eloquent.
- **Search and Sorting**: Capabilities for searching and sorting data within the table.
- **Configurable Columns**: Full control over the appearance and formatting of table columns.
- **Responsive Design**: Designed with responsiveness and mobile device compatibility in mind.
- **Easy Integration with Vue.js 3**: Simple to use in Vue.js 3 projects.

## Requirements
- Laravel 8.x or higher
- Vue.js 3.x
- PHP 7.4 or higher

## Installation
Install the package via composer:

```bash
composer require atakujemnie/laravel-vue3-table
```
## Usage

### Simple Usage
After adding the package to your application, you can easily set up a table by running the following command:
```bash
php artisan make:table MyCustomTable MyModel
```
In this command:

* MyCustomTable is the name of the class responsible for loading table data from the backend.
* MyModel is your Laravel model from which the data will be sourced. By default, the model path is set to App/Models.
This command will create a new "Tables" folder in the App directory with your custom table class. This class includes a basic method that allows you to load your model into the table.

To utilize your table data, you might do something like this:

``` bash
$tableModel = new MyCustomTable();
$tableData = $tableModel->getTableData($request);
return response()->json($tableData);
```

Remember, the table relies on Illuminate\Http\Request data for passing parameters such as pagination, etc. The data you receive can be directly connected to the Table component described below.

### Using Frontend Functionality
If your application does not include views, you can utilize only the backend functionalities of this package. However, if you wish to use the table component in your views, follow the steps below:

Publishing Vue.js Components
To use the table component within your views, you need to publish the Vue.js components from this package to your application. Run the following command:

```bash
php artisan vendor:publish --tag=laravel-vue3-table-components
```
This command will create a LaravelVueTable folder within your Components directory, containing the Table component and its associated elements. Now, you are ready to integrate the Table component into your application views.

```vue
<template>
  <Table :apiUrl="'http://yourdomain.com/api/datatable'" />
</template>

<script>
import Table from '@/Components/LaravelVueTable/Table.vue';

export default {
    components: {
        Table,
    },
};
</script>
```


## Contributing
Contributions are welcome and will be fully credited. Please see CONTRIBUTING for details.

## License
The Laravel Vue3 Table is open-sourced software licensed under the MIT license.


Feel free to adjust or add any specific details about your package that you think are importa
