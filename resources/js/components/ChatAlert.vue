<template>
    <div v-if="showAlert">
        <alert placement="top-right" :duration="4000" type="success" width="400px">
            <button type="button" @click="close" class="close">
                <span>x</span> 
            </button>
            
            <span class="icon-ok-circled alert-icon-float-left"></span>
            <strong>Has recibido un mensaje!</strong>
            <a :href="`${location}#chat_form`">Ver mensaje</a>
        </alert>
    </div>
    <!-- <alert placement="top-right" :duration="4000" type="success" width="400px" dismissable> -->
</template>

<script>
    import { alert } from 'vue-strap'//importa el componente

    export default {
        components: {
            alert
        },
       
        data() {
            return {
                showAlert: false,
                location: ''
            }
        },

        methods: {
            close(){
                this.showAlert = false;
            }
        },

        mounted() {
            window.Echo.channel('chat-added').listen('ChatEvent', (e) => {
                this.showAlert = true;
                this.location = window.location.pathname;
            });
        }
    }
</script>
