<template>
    <div class="layout">
        <!-- Cabecera -->
        <Header />
        <main>
            <div class="container">
                <!-- Contenido -->
                <slot></slot>
            </div>
        </main>
        <!-- Pie de página -->
        <footer>
            <p>&copy; Prueba de Evaluación - Edwar Villavicencio</p>
        </footer>
    </div>
</template>

<script>
import Header from '@/Layouts/Header.vue';

export default {
    name: 'Layout',
    components: {
        Header
    }
}
</script>

<style scoped>
.layout {
    min-height: 100vh;
    display: flex;
    flex-direction: column;
}

main {
    padding: 1rem 0 1rem 0;
}

footer {
    margin-top: auto;
    background: #292828;
    color: white;
    text-align: center;
    padding: 10px;
}

.container {
    padding-bottom: 40px;
    padding-top: 10px;
}
</style>
