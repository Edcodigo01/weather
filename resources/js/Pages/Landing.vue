<template>
    <div>
        <h4 class="title"> Usuarios y clima actual</h4>
        <!-- Componente tabla reutilizable -->
        <Table :headers="headers" :items="items" :total="total" :filters="filters" url_actions="/">
            <!-- Definir las filas a través del slot -->
            <template #rows>
                <tr v-for="item in items.data">
                    <td> {{ item.name }} </td>
                    <td> {{ item.email }} </td>
                    <td>
                        <span v-if="item.temp_c">
                            <img class="icon-weather" :src="item.condition_icon" alt="icon-weather">
                            {{ item.temp_c }} °C - {{ item.condition_text }}
                        </span>
                    </td>
                    <td>
                        <button v-if="item.temp_c" @click="openModal(item.id)" data-bs-toggle="tooltip"
                            title="Este es un tooltip!" class="btn-primary btn-xs"> <i class="bi bi-list"></i> </button>
                    </td>
                </tr>
            </template>
        </Table>

        <!-- Componente modal -->
        <ModalWeatherUser ref="modal">
        </ModalWeatherUser>
    </div>
</template>

<script>
import Table from '@/Partials/Table.vue';
import Layout from '@/Layouts/Layout.vue';
import ModalWeatherUser from '@/Partials/ModalWeatherUser.vue';

export default {
    layout: Layout,
    components: {
        Table,
        ModalWeatherUser,
    },
    props: {
        items: Object,
        total: Number,
        filters: Object,
    },
    data() {
        return {
            headers: [{ title: "Nombre", name: "name", sort: true }, { title: "Correo", name: "email", sort: true }, { title: "Temperatura", name: "weather", sort: false }, { title: "Detalles", name: "detail", sort: false }],
            loading: false
        };
    },
    methods: {
        //Abrir modal
        openModal(userid) {
            this.$refs.modal.open(userid);
        },
    },
}
</script>

<style>
.title {
    font-weight: 600;
    text-shadow: 1px 1px 2px rgb(253, 253, 253);
    font-family: "Poppins", sans-serif;
}

.icon-weather {
    width: 50px;
}
</style>
