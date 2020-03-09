<template>
    <v-card>
        <v-card-title>
            Sent Emails
            <v-spacer></v-spacer>
            <v-text-field
                v-model="search"
                append-icon="mdi-magnify"
                label="Search"
                single-line
                hide-details
            ></v-text-field>
        </v-card-title>
        <v-data-table
            :headers="headers"
            :items="sentEmails"
            :search="search"
        >
        </v-data-table>
    </v-card>
</template>
<script>

    export default {
        data () {
            return {
                search: '',
                headers: [

                    { text: 'To', value: 'to' },
                    { text: 'Subject', value: 'subject' },
                    { text: 'Message', value: 'message' },
                    { text: 'Service', value: 'service' },
                    { text: 'On', value: 'created_at' },

                ],
                sentEmails: [],
            }
        },
        methods: {

            getSendEmails (){

                var self = this

                this.$root.$emit('loading', true)


                axios.get('/api/v1/sent/emails')

                    .then(function (response) {

                        self.$root.$emit('loading', false)

                        self.sentEmails = response.data

                    })
                    .catch(function (error) {

                        self.$root.$emit('loading', false)

                        flash('Something went wrong while retrieving sent emails', 'error')
                    })
                    .finally(function () {
                        self.$root.$emit('loading', false)

                    });
            }
        },
        mounted() {
            this.getSendEmails()
        }
    }
</script>
