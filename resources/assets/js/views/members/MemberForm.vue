<template> 
    <div :class="isModal ? '' : 'card card-default'"> 
  
              
            <form @submit.prevent="onSubmit" @keydown="form.errors.clear()"> 
                

                <div :class="isModal ? '' : 'card-body'">
                    <div class="form-group">
                        <h3 class="card-title">Member</h3> 
                    </div>    
                    <div class="alert" :class="form.errors.length ? 'alert-danger' : 'alert-success'" role="alert" v-show="form.message.length"  v-text="form.message"></div>
                    <div class="form-group">
                        <label for="add-first-name">First Name :</label> 
                        <span class="" v-if="pageView=='show'"  v-text="form.first_name"></span>
                        <input type="text" id="add-first-name" v-if="pageView=='edit' || pageView=='add'"  class="form-control"  placeholder="First Name" v-model="form.first_name" required /> 
                        <span class="" v-if="form.errors.has('first_name')"  v-text="form.errors.get('first_name')"></span>
                        {{setData}}
                    </div>

                    <div class="form-group">
                        <label for="add-last-name">Last Name :</label> 
                        <span class="" v-if="pageView=='show'"  v-text="form.last_name"></span>
                        <input type="text" id="add-last-name" v-if="pageView=='edit' || pageView=='add'"  class="form-control"  placeholder="Last Name" v-model="form.last_name" required /> 
                        <span class="" v-if="form.errors.has('last_name')"  v-text="form.errors.get('last_name')"></span> 
                    </div>

                    <div class="form-group">
                        <label for="add-email">Email :</label> 
                        <span class="" v-if="pageView=='show'"  v-text="form.email"></span>
                        <input type="text" id="add-email" v-if="pageView=='edit' || pageView=='add'"  class="form-control"  placeholder="Email" v-model="form.email" required /> 
                        <span class="" v-if="form.errors.has('email')"  v-text="form.errors.get('email')"></span> 
                    </div>

                    <div class="form-group " v-if="pageView=='edit'">
                        <label for="current-password">Current Password :</label>  
                        <input type="password" id="current-password" v-if="pageView=='edit'"  class="form-control"  placeholder="Password" value="secret" disabled="true" readonly="true"  />  
                    </div>

                    <div class="form-group" v-if="pageView=='edit' || pageView=='add'">
                        <label for="add-password">New Password :</label>  
                        <input type="password" id="add-password" v-if="pageView=='edit' || pageView=='add'"  class="form-control"  placeholder="Password" v-model="form.password"  /> 
                        <span class="" v-if="form.errors.has('password')"  v-text="form.errors.get('password')"></span> 
                    </div>

                    <div class="form-group" v-if="pageView=='edit' || pageView=='add'" :class="{'has-danger':incorrectPw}">
                        <label for="confirm-password">Confirm Password :</label>  
                        <input type="text" id="confirm-password" v-if="pageView=='edit' || pageView=='add'" class="form-control form-control-danger"   placeholder="Confirm Password" v-model="form.confirm_password" @blur="confirmPw"/> 
                        <span class="" v-if="form.errors.has('confirm_password')"  v-text="form.errors.get('confirm_password')"></span> 
                    </div>

                    <div class="form-group">
                        <label for="add-mobile">Contact No. / Phone No.:</label> 
                        <span class="" v-if="pageView=='show'"  v-text="form.mobile_no"></span>
                        <input type="text" id="add-mobile" v-if="pageView=='edit' || pageView=='add'"  class="form-control"  placeholder="Contact No." v-model="form.mobile_no"  /> 
                        <span class="" v-if="form.errors.has('mobile_no')"  v-text="form.errors.get('mobile_no')"></span> 
                    </div>
 
                    <div class="form-group">
                        <label for="add-address">Address :</label> 
                        <span class="" v-if="pageView=='show'"  v-text="form.address"></span>
                        <input type="text" id="add-address" v-if="pageView=='edit' || pageView=='add'"  class="form-control"  placeholder="Address" v-model="form.address"  /> 
                        <span class="" v-if="form.errors.has('address')"  v-text="form.errors.get('address')"></span> 
                    </div>

                    <div class="form-group">
                        <label for="add-city">City :</label> 
                        <span class="" v-if="pageView=='show'"  v-text="form.city"></span>
                        <input type="text" id="add-city" v-if="pageView=='edit' || pageView=='add'"  class="form-control"  placeholder="City" v-model="form.city"  /> 
                        <span class="" v-if="form.errors.has('city')"  v-text="form.errors.get('city')"></span> 
                    </div>

                    <div class="form-group">
                        <label for="add-province">Province :</label> 
                        <span class="" v-if="pageView=='show'"  v-text="form.province"></span>
                        <input type="text" id="add-province" v-if="pageView=='edit' || pageView=='add'"  class="form-control"  placeholder="Province" v-model="form.province"  /> 
                        <span class="" v-if="form.errors.has('province')"  v-text="form.errors.get('province')"></span> 
                    </div>

                    <!-- Hidden Fields -->
                    <input type="hidden" v-model="form.image_url"  /> 
                    <input type="hidden" v-model="form.up_user_id"  /> 

                      
                    <div class="form-group">
                        <button class="btn btn-primary" :disabled="form.errors.any()" v-show="pageView=='edit' || pageView=='add'">{{btnName}}</button> 
                        <router-link :to="`/members/${form.id}/edit`" class="btn btn-secondary" v-show="pageView=='show'"  >Edit</router-link>    
                        <button class="btn btn-danger" @click.prevent="onDelete(form.id)" v-show="pageView=='edit'">Delete</button> 
                        <button class="btn btn-default" @click.prevent="onCancel()" >Cancel</button> 
                    </div>
                </div> 

            </form>    
    
    </div>    
</template>

<script>
    import Member from '../../models/Member';

    export default{
        props: ['isModal', 'id', 'first_name', 'last_name', 'email', 'mobile_no', 'address', 'city', 'province', 'image_url', 'up_user_id', 'pageView'],

        data(){
            return{
                form: new Form({id:'', first_name:'', last_name:'', email:'', mobile_no:'', address:'', city:'', province:'', image_url:'', up_user_id:'', password:'', confirm_password:'', message: ''}),
                btnName: 'Save', 
                rid: this.$route.params.rid,
                incorrectPw: true,
            }
        },

       

        methods: {
            confirmPw(){
                //alert("confirmPw:" + this.form.password +' == '+this.form.confirm_password);

            },
            fetchRecord(id){
 
                Member.find(id, result => {   
                    //console.log("before post: "+JSON.stringify(result));
                    let record = result.data[0];  
                    for(let r in record){
                        this.form[r] = record[r]; 
                    }    
                    //console.log("after post: "+JSON.stringify(this.form));
                });

                 
                
            },
            onSubmit(){ 
                
                if(this.pageView == "add"){
                    this.form
                    .post('/pipe/members')
                    .then(result => {
                        if(this.isModal){
                            this.$emit('added', result.data);
                            this.reset();
                        }    

                        this.$router.push('/products');
                        
                    });
                }else{
                    this.form
                    .put('/pipe/members')
                    .then(result => {
                        if(this.isModal){
                            this.$emit('updated', result.data);
                            this.reset();
                        }  

                        this.form.message = "Saved successfully.";
                    });
                }
                 
                
            },

            onDelete(id){  
                 this.form
                    .delete('/pipe/members/'+id)
                    .then(record => {
                        if(this.isModal){
                            this.$emit('deleted', record.data.id)
                        }else{
                           console.log("delete: "+id); 
                           this.$router.push('/products'); 
                        }
                    });
            },

            onCancel(){  
                if(this.isModal){
                    this.$emit('cancel');
                }else{
                    this.$router.go(-1); 
                }

            }, 

            reset(){  
                
                this.form.reset();
            }, 

            

        },

        created(){
            
        },

        computed: {
            setData(){
                if(this.pageView == "add"){
                    this.btnName = "Save";
                }else{
                    this.btnName = "Update";
                }

                
                console.log("pageView: "+this.pageView); 
                console.log("id: "+this.id); 
                // console.log("name: "+this.name); 
                // console.log("price: "+this.price);  

                if(this.isModal == true){ 
                    this.form.id = this.id;
                    // this.form.name = this.name;
                    // this.form.price = this.price;
                    //this.form.user_id = this.user_id;
                }else{
                    //this.rid = this.$route.params.rid;
                    if(this.pageView == 'show' || this.pageView == 'edit')
                        this.fetchRecord(this.rid);

                }

                
            }
        },
         
        mounted() { 
            
        }

        
    }

</script>