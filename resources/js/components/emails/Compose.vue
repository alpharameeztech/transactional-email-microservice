<template>
    <v-container>

        <p class="display-1">Compose Email</p>
        <v-form
            ref="form"
            v-model="valid"
            lazy-validation
        >
            <v-text-field
                v-model="email"
                :rules="emailRules"
                label="E-mail"
                required
            ></v-text-field>

            <v-text-field
                v-model="subject"
                :counter="100"
                :rules="subjectRules"
                label="Subject"
                required
            ></v-text-field>

            <v-textarea
                v-model="message"
                :counter="500"
                :rules="messageRules"
                name="message"
                label="What's on your mind?"
            ></v-textarea>

            <v-checkbox
                v-model="checkbox"
                :rules="[v => !!v || 'You must agree to continue!']"
                label="Do you agree?"
                required
            ></v-checkbox>

            <v-btn
                :disabled="!valid"
                color="success"
                class="mr-4"
                @click="validate"
            >
                Validate
            </v-btn>

            <v-btn
                color="error"
                class="mr-4"
                @click="reset"
            >
                Reset Form
            </v-btn>

            <v-btn
                color="warning"
                @click="resetValidation"
            >
                Reset Validation
            </v-btn>
        </v-form>


    </v-container>
</template>

<script>
    export default {
        data: () => ({
            valid: true,
            subject: '',
            subjectRules: [
                v => !!v || 'Subject is required',
                v => (v && v.length <= 100) || 'Subject must be less than 100 characters',
            ],
            email: '',
            emailRules: [
                v => !!v || 'E-mail is required',
                v => /.+@.+\..+/.test(v) || 'E-mail must be valid',
            ],
            message: '',
            messageRules: [
                v => !!v || 'Message is required',
                v => (v && v.length <= 500) || 'Message must be less than 500 characters',
            ],
            checkbox: false,
        }),

        methods: {
            validate () {
                if (this.$refs.form.validate()) {
                    this.snackbar = true
                    this.submit()
                }
            },
            reset () {
                this.$refs.form.reset()
            },
            resetValidation () {
                this.$refs.form.resetValidation()
            },
            submit (){

                var self = this

                this.$root.$emit('loading', true)

                let formData = new FormData();
                /*
                    Add the form data we need to submit
                */
                formData.append('to', this.email)
                formData.append('subject', this.subject)
                formData.append('message', this.message)

                axios.post('/api/v1/send/email', formData)

                    .then(function (response) {

                        self.$root.$emit('loading', false)

                        flash('Email successfully sent to ' + self.email)

                        self.reset()

                    })
                    .catch(function (error) {
                        console.log(error)
                        self.$root.$emit('loading', false)

                        flash('Oops, something went wrong.', 'error')
                    })
                    .finally(function () {
                        self.$root.$emit('loading', false)

                    });
            }
        },
    }
</script>
