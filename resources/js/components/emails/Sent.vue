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
            <template v-slot:item.message="{ item }">
                <v-row  class="d-flex justify-end">
                    {{ item.message}}
                    <v-chip
                        class="ma-2"
                        color="primary"
                        outlined
                        pill
                    >
                        <v-avatar left>
                            <v-icon>av_timer</v-icon>
                        </v-avatar>

                        {{ ago(item.created_at) }}

                    </v-chip>
                </v-row>
            </template>
        </v-data-table>
    </v-card>
</template>
<script>

    import moment from 'moment';

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
            },
            ago(date){

                moment.locale();

                return moment.utc(date).fromNow();

            },
        },
        mounted() {
            this.getSendEmails()
        }
    }
</script>
