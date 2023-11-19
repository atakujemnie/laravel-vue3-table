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
  <Table :apiUrl="'http://yourdomain.com/api/data-for-table'" />
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

## Custom Table Columns

### Creating Custom Columns

To enhance the functionality of your tables with custom columns, you can define additional data that goes beyond the default model attributes. These custom columns can be used to display computed values, aggregate information, or options like edit links.

Here's how you can define custom columns in your `TableService` subclass:

```php
protected function getAdditionalColumns(): void
{
    $this->additionalColumns = [
        [
            'name' => 'eans',
            'label' => 'EANs',
            'sortable' => false,
            'searchable' => false,
            'additional' => true,
            'contentQuery' => [$this, 'getEANsForProduct']
        ],
        [
            'name' => 'edit',
            'label' => 'Edit',
            'sortable' => false,
            'searchable' => false,
            'additional' => true
        ],
        // Add more custom columns as needed
    ];
}
```

## Implementing Content Queries
For each custom column that requires additional data from the backend, you should implement a corresponding method that retrieves this data. Here's an example for the eans custom column:

```php
protected function getEANsForProduct($productId)
{
    $product = Product::find($productId);
    return $product ? $product->variants->pluck('ean') : null;
}
```

Displaying Custom Columns in the Frontend
In your frontend Vue.js component, you can utilize slots to render the content of custom columns. Below is an example of how you could display the eans and edit custom columns:

```vue
<template v-slot:column-extra="{ column, item }">
    <div v-if="column.name === 'edit'">
        <a :href="'/edit/' + item.id">Edit</a>
    </div>
    <div v-if="column.name === 'eans'">
        <span v-for="ean in item.eans" :key="ean">{{ ean }}</span>
    </div>
</template>
```

Make sure to use the correct column names and data properties that match your backend configuration.

Conclusion
Custom columns allow for a highly flexible and dynamic table that can fit the unique needs of your application. With custom content queries and the power of Vue.js slots, you can easily add and display any type of data in your tables.

## Contributing
Contributions are welcome and will be fully credited. Please see CONTRIBUTING for details.

## License
The Laravel Vue3 Table is open-sourced software licensed under the MIT license.


Feel free to adjust or add any specific details about your package that you think are importa
