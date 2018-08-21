<template>
        <div class="customer-new">
            <div class="errors" v-if="errors">
                <ul>
                    <li v-for="(fieldsError, fieldName) in errors" :key="fieldName">
                        <strong>{{ fieldName }}</strong> {{ fieldsError.join('\n') }}
                    </li>
                </ul>
            </div>
            <form @submit.prevent="add">
                <table class="table">
                    <tr>
                        <th>Name</th>
                        <td>
                            <input type="text" class="form-control" v-model="customer.name" placeholder="Name"/>
                        </td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td>
                            <input type="email" class="form-control" v-model="customer.email" placeholder="Email"/>
                        </td>
                    </tr>
                    <tr>
                        <th>Phone</th>
                        <td>
                            <input type="text" class="form-control" v-model="customer.phone" placeholder="Phone"/>
                        </td>
                    </tr>
                    <tr>
                        <th>Address</th>
                        <td>
                            <input type="text" class="form-control" v-model="customer.address" placeholder="Address"/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <router-link to="/customers" class="btn">Cancel</router-link>
                        </td>
                        <td class="text-right">
                            <input type="submit" value="Create" class="btn btn-primary">
                        </td>
                    </tr>
                </table>
                </form>
    
        </div>
    </template>
    
    <script>
    import validate from 'validate.js';
    
    export default {
        name: 'new',
        data() {
            return {
                customer: {
                    name: '',
                    email: '',
                    phone: '',
                    address: ''
                },
                errors: null
            };
        },
        computed: {
            currentUser(){
                return this.$store.getters.currentUser;
            }
        },
        methods: {
            add() {
                this.errors = null;
    
                const constraints = this.getConstraints();
                const errors = validate(this.$data.customer, constraints);
    
                if(errors) {
                    this.errors = errors;
                    return;
                }
    
                console.log("post"+JSON.stringify(this.$data.customer));
                axios.post('/api/customers/new', this.$data.customer)
                    .then((response) => {
                        this.$router.push('/customers');
                    });
    
                //send the customer data to the api.
            },
            getConstraints() {
                return {
                    name: {
                        presence: true,
                        length: {
                            minimum: 3,
                            message: 'Must be at least 3 characters long'
                        }
                    },
                    email: {
                        presence: true,
                        email: true
                    },
                    phone: {
                        presence: true,
                        numericality: true,
                        length: {
                            minimum: 10,
                            message: 'Must be at least 10 digits long'
                        }
                    }
                };
            }
        }
    }
    </script>
    
    
    <style>
    .errors {
        color: lightcoral;
        border-radius:5px;
        padding: 21px 0 2px 0;
    }
    </style>