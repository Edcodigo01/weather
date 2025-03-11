<template>

    <div class="card">
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <!-- Icono ordenar si se marca en true en headers -->
                        <th v-for="header in headers" @click="sortTable(header)">
                            {{ header.title }}
                            <span v-if="header.sort" v-html="getIconSort(header.name)"></span>
                        </th>
                    </tr>

                </thead>
                <tbody>
                    <!-- Renderiza las filas -->
                    <slot name="rows"></slot>
                </tbody>
            </table>

            <div class="row pt-2">
                <div class="col-4">
                    <!-- Opción para cambiar el número de registros por página -->
                    <div class="form-group" style="width: 110px;">
                        <div class="input-group input-group mb-3">
                            <span class="input-group-text" id="basic-addon1"> <i class="bi bi-eye-fill"></i> </span>
                            <select class="form-control form-control-sm form-select" v-model="perPage"
                                @change="reloadTable">
                                <option value="5">5</option>
                                <option value="10" selected>10</option>
                                <option value="20">20</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-4 text-center pt-2">
                    <!-- Datos de pagina -->
                    <p> <strong>Mostrando {{ start }} a {{ end }} de {{ total }} registros</strong> </p>
                </div>
                <div class="col-4">
                    <!-- Paginación -->
                    <ul class="pagination float-end">
                        <li class="page-item" :class="{ 'disabled': items.current_page <= 1 }">
                            <button class="page-link" @click="goToPage(items.current_page - 1)"><i
                                    class="bi bi-chevron-left"></i></button>
                        </li>
                        <li class="page-item" v-for="page in items.last_page" :key="page"
                            :class="{ 'active': page === items.current_page }">
                            <button class="page-link" @click="goToPage(page)">{{ page }}</button>
                        </li>
                        <li class="page-item" :class="{ 'disabled': items.current_page >= items.last_page }">
                            <button class="page-link" @click="goToPage(items.current_page + 1)"> <i
                                    class="bi bi-chevron-right"></i> </button>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import LoaderSpinner from '../Partials/LoaderSpinner.vue';

export default {
    components: {
        LoaderSpinner
    },
    props: {
        items: Object,
        total: Number,
        filters: Object,
        headers: Array,
        url_actions: String
    },
    data() {
        return {
            current_page: this.filters.page,
            perPage: this.filters.per_page,
            sortBy: this.filters.sort_by,
            sortOrder: this.filters.sort_order
        };
    },
    computed: {
        start() {
            return (this.items.current_page - 1) * this.perPage + 1;
        },
        end() {
            const end = this.items.current_page * this.perPage;
            return end > this.total ? this.total : end;
        }
    },
    methods: {
        // Ordenar por columna
        sortTable(header) {
            if (!header.sort) return;
            let column = header.name;
            const newOrder = this.sortBy === column && this.sortOrder === 'asc' ? 'desc' : 'asc';
            this.sortBy = column;
            this.sortOrder = newOrder;
            this.reloadTable()
        },
        // Cambio de página
        goToPage(page) {
            this.current_page = page;
            if (page >= 1 && page <= this.items.last_page) {
                this.reloadTable()
            }
        },
        // Actualizar el número de registros por página
        getIconSort(name) {
            if (this.sortBy != name) return `<i class="bi bi-arrow-down-up text-secondary hover"></i>`;
            else if (this.sortOrder == 'asc') return `<i class="bi bi-sort-down-alt hover"></i>`;
            else return `<i class="bi bi-sort-down hover"></i>`;
        },
        // Recargar tabla
        reloadTable() {
            this.$inertia.get(`${this.url_actions}?page=${this.current_page}&per_page=${this.perPage}&sort_by=${this.sortBy}&sort_order=${this.sortOrder}`, {}, {
                preserveState: true,
                replace: true
            });
        }
    }
}
</script>

<style>
.bi-arrow-down-up {
    font-size: 14px;
}

.table td {
    padding: 5px !important;
    vertical-align: middle;
}

.table th {
    padding: 5px !important;
}
</style>
