<template>
    <v-container
        class="fill-height"
        fluid
    >
        <v-row
            align="center"
            justify="center"
        >
            <div v-show="show" v-cloak>
                <v-alert text dense :type="type">
                    {{ body }}
                </v-alert>
            </div>
        </v-row>

    </v-container>
</template>

<script>
    export default {
        props: ['message'],

        data(){
            return {
                body: '',
                show: false,
                type: 'success'
            }
        },
        created(){

            //listen for the event flash-message along with  a props and then fire it
            window.events.$on('flash', data => {

                this.flash(data)

                this.hide();

            });

        },

        methods: {

            flash(data) {

                this.body = data.message
                this.show = true;
                this.type = data.type;

            },

            flashMessage(message){
                this.body = message;
                this.show = true;
            },
            hide() {

                setTimeout(() => {
                    this.show = false;
                }, 3000);

            }
        },

    }
</script>

