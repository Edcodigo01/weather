<template>
    <header>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">
                <a class="navbar-brand" href="#"> <img class="logo-nav" :src="'/images/thetime.png'" alt="Logo"> El
                    Tiempo</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <!-- <li class="nav-item">
                                <a class="nav-link active" aria-current="page" href="#">Home</a>
                            </li> -->
                    </ul>
                </div>
            </div>
        </nav>
    </header>
</template>

<script>
export default {
    name: 'Layout',
}
</script>

<style scoped>
.navbar-brand {
    color: white !important;
    font-family: "Poppins", sans-serif;
    margin-left: 20px;
    text-shadow: 1px 1px 2px grey;
    font-size: 22px;
    font-weight: 600;
}

.logo-nav {
    width: 35px;
    border-radius: 100%;
    border: solid 2px white;
}

.navbar {
    background: linear-gradient(to right, #00A2B7, #66C9D5);
}
</style>
