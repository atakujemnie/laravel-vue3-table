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
Publish the Vue component:
```bash
php artisan make:vue-component
```
Add the Vue component to your Vue application.

Use the ApiTable component in your Vue templates:

```vue
<template>
  <ApiTable :apiUrl="'http://yourdomain.com/api/datatable'" />
</template>

<script>
import ApiTable from '@/Components/Tables/ApiTable.vue';

export default {
    components: {
        ApiTable,
    },
};
</script>
```
Create a new table class in Laravel:
```bash
php artisan make:table MyCustomTable
```
Define your table logic in app/Tables/MyCustomTable.php.

## Contributing
Contributions are welcome and will be fully credited. Please see CONTRIBUTING for details.

## License
The Laravel Vue3 Table is open-sourced software licensed under the MIT license.


Feel free to adjust or add any specific details about your package that you think are importa
