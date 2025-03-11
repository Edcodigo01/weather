<template>
    <!-- Loader spinner -->
    <LoaderSpinner v-if="loading" />

    <div class="modal-p-overlay" v-if="isVisible" @click.self="close">
        <div class="modal-p fade show">
            <div class="card">

                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <h5 class="title m-0 float-end"> Clima actual: <span class="text-primary">{{
                                weatherData.name }}</span>
                            </h5>
                            <img class="icon-weather mb-2" :src="weatherData.condition_icon" alt="icon-weather">
                        </div>
                    </div>
                    <div class="row">
                        <!-- <p> {{ weatherData.condition_icon }} </p> -->
                        <div class="col-4">
                            <label>Ubicación</label>
                            <p> {{ weatherData.location }} </p>
                        </div>
                        <div class="col-4">
                            <label>Región</label>
                            <p> {{ weatherData.region }} </p>
                        </div>
                        <div class="col-4">
                            <label>País</label>
                            <p> {{ weatherData.country }} </p>
                        </div>
                        <div class="col-4">
                            <label> Hora local</label>
                            <p> {{ weatherData.localtime }} </p>
                        </div>
                        <div class="col-4">
                            <label>Descripción del clima</label>
                            <p> {{ weatherData.condition_text }} </p>
                        </div>
                        <div class="col-4">
                            <label>Temperatura en Celsius</label>
                            <p> {{ weatherData.temp_c }} </p>
                        </div>

                        <div class="col-4">
                            <label>Momento del Día</label>
                            <p> <span v-if="weatherData.is_day">Día</span> <span v-else=>Noche</span> </p>
                        </div>
                        <div class="col-4">
                            <label>Dirección del viento</label>
                            <p> {{ weatherData.wind_dir }} </p>
                        </div>
                        <div class="col-4">
                            <label>Velocidad del viento </label>
                            <p> {{ weatherData.wind_kph }} kph </p>
                        </div>
                        <div class="col-4">
                            <label>Temperatura "real"</label>
                            <p> {{ weatherData.feelslike_c }} Celsius </p>
                        </div>
                        <div class="col-4">
                            <label> Ráfagas de viento en kph </label>
                            <p> {{ weatherData.gust_kph }} </p>
                        </div>
                    </div>

                </div>
                <div class="card-footer py-3 bg-white">
                    <button class="btn btn-primary m-0 float-end" @click.self="close"> <i class="bi bi-x-lg"></i>
                        Cerrar</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import LoaderSpinner from '@/Partials/LoaderSpinner.vue';
import { toast } from "vue3-toastify";

export default {
    components: {
        LoaderSpinner,
    },
    data() {
        return {
            loading: false,
            isVisible: false,
            weatherData: {},
            urlUsers: "/users",
        };
    },
    methods: {
        close() {
            this.isVisible = false;
        },
        async open(userId) {
            this.loading = true;
            try {
                const response = await this.$axios.get(`${this.urlUsers}/${userId}`);
                this.weatherData = response.data;
                this.isVisible = true;
                this.loading = false;

            } catch (error) {
                this.loading = false;

                if (error.status == 404)
                    toast.error("El usuario al que intenta acceder no existe o fue deshabilitado");
                else {
                    toast.error("Algo salió mal.");
                    console.error('Error al obtener los datos:', error);
                }
            }
        },
    },

};
</script>

<style scoped>
.modal-p-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 999;
}

.modal-p {
    width: 80%;
    max-width: 900px;
    z-index: 999;
}

button {
    margin-top: 10px;
}

.modal {
    display: block;
}

.icon-weather {
    width: 60px;
}
</style>
