<template>
    <div class="text-center">
        <v-overlay :value="overlay" >
            <v-progress-circular indeterminate size="64"></v-progress-circular>
        </v-overlay>
    </div>
</template>

<script>
    export default {
        data: () => ({
            overlay: false,
        }),
        methods: {

        },
        mounted() {
            this.$root.$on('loading', data => {
                this.overlay = data
            });
        }
    }
</script>
