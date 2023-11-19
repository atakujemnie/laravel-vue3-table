<template>
    <div class="container mx-auto p-4">
        <!-- Inputs and Selects in one line -->
        <div class="flex flex-col md:flex-row justify-between items-center mb-4 space-y-2 md:space-y-0">
            <!-- Search Input -->
            <input type="text" v-model="searchTerm" @input="fetchData"
                class="p-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-gray-200"
                placeholder="Search..." />

            <!-- Select for showing/hiding columns, only show if there are hidden columns -->
            <div v-if="hiddenColumns.length > 0" class="w-full">
                <select v-model="visibleColumns"
                    class="p-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-gray-200 w-full">
                    <option value="" disabled selected>Select column to show</option>
                    <option v-for="column in hiddenColumns" :key="column.name" :value="column.name">
                        {{ column.label }}
                    </option>
                </select>
            </div>
            <!-- Select for 'Products per page' -->
            <div>
                <label for="perPage" class="mr-2">Products per page:</label>
                <select id="perPage" v-model="perPage" @change="fetchData"
                    class="pr-10 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-gray-200">
                    <option value="10">10</option>
                    <option value="15">15</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                </select>
            </div>
        </div>

        <div class="overflow-hidden shadow-lg rounded-lg border border-gray-200">
            <table class="min-w-full bg-white">
                <thead class="bg-gradient-to-r from-gray-300 to-gray-200">
                    <tr class="uppercase text-sm leading-normal text-gray-700">
                        <th v-for="column in visibleColumnsArray" :key="column.name"
                            @click="column.sortable ? sortBy(column) : null"
                            class="py-3 px-6 text-left font-semibold cursor-pointer whitespace-nowrap">
                            {{ column.label }}
                            <TableSortIcon v-if="column.sortable" :isSorted="sortColumn === column.name"
                                :sortDirection="sortDirection" />
                        </th>

                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-300">
                    <tr v-for="item in data" :key="item.id" class="hover:bg-gray-100">
                        <td v-for="column in visibleColumnsArray" :key="column.name" class="py-3 px-6">
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
        </div>
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
import TableSortIcon from './tableElements/TableSortIcon.vue';

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
    components: {
        TableSortIcon,
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
            screenWidth: window.innerWidth,
            visibleColumns: [],
        };
    },
    computed: {
        visibleColumnsArray() {
            // Upewnij się, że columns istnieje zanim użyjesz go w obliczeniach
            if (!this.columns) {
                return [];
            }
            let numberOfColumnsToShow = this.screenWidth > 1024 ? this.columns.length : 3;
            let visible = this.columns.slice(0, numberOfColumnsToShow);
            if (this.visibleColumns && this.columns.some(column => column.name === this.visibleColumns)) {
                visible.push(this.columns.find(column => column.name === this.visibleColumns));
            }
            return visible;
        },
        hiddenColumns() {
            // Upewnij się, że columns istnieje zanim użyjesz go w obliczeniach
            if (!this.columns) {
                return [];
            }
            return this.columns.filter(column => !this.visibleColumnsArray.includes(column));
        },
        allColumnsVisible() {
            return this.visibleColumns.length === this.columns.length;
        }
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
                        this.columns = response.data.columns || [];
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
        },
        handleResize() {
            this.screenWidth = window.innerWidth;
        },
    },

    created() {
        this.fetchData();
    },
    mounted() {
        this.handleResize();
        window.addEventListener('resize', this.handleResize);
    },
    beforeDestroy() {
        window.removeEventListener('resize', this.handleResize);
    }
};
</script>

<style scoped>
@media screen and (max-width: 640px) {
    .overflow-x-auto {
        overflow-x: auto;
    }
}
</style>

