<template>
    <div class="container mx-auto p-4">
        <input type="text" v-model="searchTerm" @input="fetchData" class="mb-4 p-2 border border-gray-300 rounded"
            placeholder="Search..." />
        <div class="flex justify-end mb-4">
            <label class="mr-2">Products per page:</label>
            <select v-model="perPage" @change="fetchData" class="p-2 border border-gray-300 rounded">
                <option value="10">10</option>
                <option value="15">15</option>
                <option value="25">25</option>
                <option value="50">50</option>
            </select>
        </div>
        <table class="min-w-full border-collapse block md:table">
            <thead class="block md:table-header-group">
                <tr class="border border-gray-300 md:border-none block md:table-row">
                    <th v-for="column in columns" :key="column.name" @click="column.sortable ? sortBy(column) : null"
                        class="bg-gray-100 p-2 border border-gray-300 md:border-none block md:table-cell cursor-pointer"
                        :class="{ 'text-blue-500': sortColumn === column.name }">
                        {{ column.label }}
                        <span v-if="column.sortable">
                            <i v-if="sortColumn === column.name && sortDirection === 'asc'" class="fas fa-arrow-up"></i>
                            <i v-if="sortColumn === column.name && sortDirection === 'desc'" class="fas fa-arrow-down"></i>
                        </span>
                    </th>
                </tr>
            </thead>
            <tbody class="block md:table-row-group">
                <tr v-for="item in data" :key="item.id" class="border border-gray-300 md:border-none block md:table-row">
                    <td v-for="column in columns" :key="column.name"
                        class="p-2 border border-gray-300 md:border-none block md:table-cell">
                        <div v-if="column.content">
                            <div v-html="column.content(item)"></div>
                        </div>
                        <div v-else>
                            {{ item[column.name] }}
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="flex justify-between items-center mt-4">
            <button @click="changePage(currentPage - 1)" :disabled="currentPage === 1"
                class="px-4 py-2 bg-gray-300 text-white font-bold rounded hover:bg-gray-400 disabled:opacity-50 disabled:cursor-not-allowed">
                Prev
            </button>
            <span>Page {{ currentPage }} of {{ totalPages }}</span>
            <button @click="changePage(currentPage + 1)" :disabled="currentPage === totalPages"
                class="px-4 py-2 bg-gray-500 text-white font-bold rounded hover:bg-gray-600 disabled:opacity-50 disabled:cursor-not-allowed">
                Next
            </button>
        </div>
    </div>
</template>
  
<script>
import axios from 'axios';

export default {
    props: {
        apiUrl: {
            type: String,
            required: true
        },
        additionalParams: {
            type: Object,
            default: () => ({})
        }
    },
    data() {
        return {
            data: [],
            columns: [],
            currentPage: 1,
            totalPages: 0,
            searchTerm: '',
            sortColumn: '',
            sortDirection: 'asc',
            additionalParams: {},
            perPage: 15,
        };
    },
    methods: {
        fetchData() {
            const params = {
                page: this.currentPage,
                searchTerm: this.searchTerm,
                sortColumn: this.sortColumn,
                sortDirection: this.sortDirection,
                perPage: this.perPage,
                ...this.additionalParams
            };
            axios.get(this.apiUrl, { params })
                .then(response => {
                    if (this.currentPage > response.data.pagination.total_pages) {
                        this.currentPage = 1;
                        this.fetchData();
                    } else {
                        this.data = response.data.data;
                        this.columns = response.data.columns;
                        this.totalPages = response.data.pagination.total_pages;
                    }
                })
                .catch(error => {
                    console.error('Error fetching data:', error);
                });
        },
        sortBy(column) {
            this.sortColumn = column.name;
            this.sortDirection = this.sortColumn === column.name && this.sortDirection === 'asc' ? 'desc' : 'asc';
            this.fetchData();
        },
        changePage(page) {
            this.currentPage = page;
            this.fetchData();
        }
    },
    created() {
        this.fetchData();
    }
};
</script>
  
<style>
/* Tu możesz dodać dodatkowe style Tailwind CSS */
</style>
  