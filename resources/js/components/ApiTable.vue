<template>
    <div class="container mx-auto p-4">
        <div class="flex justify-between items-center mb-4">
            <input type="text" v-model="searchTerm" @input="fetchData"
                class="p-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-gray-200"
                placeholder="Search..." />
            <div>
                <label class="mr-2">Products per page:</label>
                <select v-model="perPage" @change="fetchData"
                    class="pt-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-gray-200">
                    <option value="10">10</option>
                    <option value="15">15</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                </select>
            </div>
        </div>

        <table class="min-w-full bg-white shadow-md rounded-md overflow-hidden">
            <thead class="bg-gradient-to-r from-gray-300 to-gray-200">
                <tr class="uppercase text-sm leading-normal text-gray-700">
                    <th v-for="column in columns" :key="column.name" @click="column.sortable ? sortBy(column) : null"
                        class="py-3 px-6 text-left font-semibold cursor-pointer">
                        {{ column.label }}
                        <span v-if="column.sortable" class="ml-2">
                            <svg v-if="sortColumn === column.name" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 inline">
                                <path v-if="sortDirection === 'asc'" stroke-linecap="round" stroke-linejoin="round"
                                    d="M4.5 15.75l7.5-7.5 7.5 7.5" />
                                <path v-else stroke-linecap="round" stroke-linejoin="round"
                                    d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                            </svg>
                            <svg v-else xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-4 h-4 inline">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                            </svg>
                        </span>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="item in data" :key="item.id"
                    :class="{ 'bg-gray-50': data.indexOf(item) % 2 === 0, 'hover:bg-gray-200': true, 'rounded-lg': true, 'text-gray-600': true }">
                    <td v-for="column in columns" :key="column.name" class="py-3 px-6">
                        <div v-if="column.additional">
                            <slot name="column-extra" :column="column" :item="item"></slot>
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
                class="px-4 py-2 bg-gray-300 text-gray-700 font-bold rounded-lg hover:bg-gray-400 disabled:opacity-50 disabled:cursor-not-allowed">
                Prev
            </button>
            <span class="text-gray-600">Page {{ currentPage }} of {{ totalPages }}</span>
            <button @click="changePage(currentPage + 1)" :disabled="currentPage === totalPages"
                class="px-4 py-2 bg-gray-500 text-white font-bold rounded-lg hover:bg-gray-600 disabled:opacity-50 disabled:cursor-not-allowed">
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

