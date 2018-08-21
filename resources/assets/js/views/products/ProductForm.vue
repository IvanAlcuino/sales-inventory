<template> 
    <div :class="isModal ? '' : 'card card-default'"> 
  
              
            <form @submit.prevent="onSubmit" @keydown="form.errors.clear()">
                

                <div :class="isModal ? '' : 'card-body'" class="col-md-10">
                    <div class="form-group">
                        <h3 class="card-title">Product</h3> 
                    </div>  

                    <transition name="fade" >
                        <div  class="form-group col-md-8 alert" style="margin-left:3rem" :class="form.errors.length ? 'alert-danger' : 'alert-success'" role="alert" v-show="form.message.length"  v-text="form.message"></div>
                    </transition>

                    <div class="form-group row">
                        <label for="add-name" class="col-form-label text-md-right col-md-2">Name :</label> 
                        
                        <div class="col-md-8">  
                            <label class="col-form-label" v-if="pageView=='show'"  v-text="form.name"></label>
                            <input type="text" id="add-name" v-if="pageView=='edit' || pageView=='add'"  class="form-control "  placeholder="Name" v-model="form.name" />
                            <div class="alert alert-danger" v-if="form.errors.has('name')"  v-text="form.errors.get('name')"></div>
                            {{setData}}
                        </div> 
                        
                    </div>

                    <div class="form-group row">
                        <label for="add-price" class="col-form-label text-md-right col-md-2">Price :</label>  
                        <div class="col-md-4">
                            <label class="col-form-label"  v-if="pageView=='show'"  v-text="form.price"></label>
                            <!-- <input type="text" onkeypress="return isNumberKey(event)" id="add-price" v-if="pageView=='edit' || pageView=='add'"  class="form-control"  placeholder="Name" v-model="form.price" />  -->
                            <b-form-input   type="number"  id="add-price" v-if="pageView=='edit' || pageView=='add'"  class="form-control"  placeholder="Name" v-model="form.price"></b-form-input>
                            <div class="alert alert-danger" v-if="form.errors.has('price')"  v-text="form.errors.get('price')"></div> 
                        </div>
                    </div>

                    

                    <div class="form-group row" v-if="pageView=='edit' || pageView=='add'">
                        <label for="add-image_url" class="col-form-label text-md-right col-md-2">Upload Image:</label> 
                        <div class="col-md-8"> 
                            <!-- <input type="file" @change="onFileSelected" id="add-image_url" v-if="pageView=='edit' || pageView=='add'"  class="form-control"  placeholder="Name"     />  -->
                            <b-form-file  ref="fileinput" @change="onFileSelected"  ></b-form-file> 
                            <div class="alert alert-danger" v-if="form.errors.has('image_url')"  v-text="form.errors.get('image_url')"></div> 
                        </div>
                    </div>

                    <div class="form-group row" v-if="form.image_url">
                        <label for="add-image_url" class="col-form-label text-md-right col-md-2">Image :</label> 
                        <div class="col-md-8">
                            <img v-bind:src="form.image_url" class="" style="width:300px;" />
                        </div>
                    </div> 

                    <div class="form-group row" >
                        <label for="add-image_url" class="col-form-label text-md-right col-md-2"></label> 
                        <div class="col-md-8">
                            <button class="btn btn-primary" :disabled="form.errors.any()" v-show="pageView=='edit' || pageView=='add'">{{btnName}}</button> 
                            <router-link :to="`/products/${form.id}/edit`" class="btn btn-secondary" v-show="pageView=='show'"  >Edit</router-link>    
                            <button class="btn btn-danger" @click.prevent="onDelete(form.id)" v-show="pageView=='edit'">Delete</button> 
                            <button class="btn btn-default" @click.prevent="onCancel()" >Cancel</button> 
                        </div>
                    </div>
                </div> 

            </form>    
    
    </div>    
</template>

<script>
    import Product from '../../models/Product';

    export default{
        props: ['isModal', 'id', 'name', 'price', 'image_url', 'pageView'],

        data(){
            return{
                form: new Form({id:'', name:'', price:'',  image_url:'', message: '', image: null}),
                btnName: 'Save',
                product:{},
                rid: this.$route.params.rid,
            }
        },

       

        methods: {
            fetchRecord(id){
                this.form.reset();
                Product.find(id, results => {   
                    //console.log("before post: "+JSON.stringify(result));
                    let result = results.data[0];  
                    for(let f in result){
                        this.form[f] = result[f]; 
                    }   
                    //console.log("after post: "+JSON.stringify(this.form));
                });

                 
                
            },
            onSubmit(){ 
                
                if(this.pageView == "add"){
                    this.form
                    .post('/pipe/products')
                    .then(post => {
                        if(this.isModal){
                            this.$emit('added', post.data);
                            this.reset(); 
                        }    

                        this.$router.push('/products');
                        
                    });
                }else{
                    this.form
                    .put('/pipe/products')
                    .then(post => {
                        if(this.isModal){
                            this.$emit('updated', post.data);
                            this.reset();
                        }   
 
                        //alert("here");
                        this.form.message = "Saved successfully.";
                        this.fetchRecord(this.form.id);  
                         
                        var that = this;
                        setTimeout(function() {
                            that.form.message = "";
                        }, 3000);
                        
                    });
                }
                 
                
            },

            onDelete(id){  
                 this.form
                    .delete('/pipe/products/'+id)
                    .then(post => {
                        if(this.isModal){
                            this.$emit('deleted', post.data.id)
                        }else{ 
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
                this.$refs.fileinput.reset();
            }, 

            onFileSelected(e){
                
                let files = e.target.files || e.dataTransfer.files;
                if (!files.length)
                    return;
                
               
                if(files[0].size > 3000000){
                    //alert("above.."+files[0].size);
                    //this.form.errors.set("image_url", "Image is too large. Please limit the file size to 3 Megabyte.");
                    let err = {image_url: ["Image is too large. Please limit the file size to 3 Megabyte."]}
                    this.form.errors.record(err);
                    //alert(this.form.errors.get("image_url"));
                    //this.$refs.fileinput.reset();
                    return false;
                }else{ 
                    //alert("below.."+files[0].size);
                    this.form.errors.record({});
                }                    
                this.createImage(files[0]);
            },

            createImage(file) {
                let reader = new FileReader();
                let vm = this;
                reader.onload = (e) => {
                    vm.form.image = e.target.result;
                };
                reader.readAsDataURL(file);
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
                console.log("name: "+this.name); 
                console.log("price: "+this.price);  

                if(this.isModal == true){ 
                    this.form.id = this.id;
                    this.form.name = this.name;
                    this.form.price = this.price;
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

<style>
     
    .fade-enter-active,
    .fade-leave-active {
        transition: opacity 1s
    }

    .fade-enter,
    .fade-leave-to 
    {
        opacity: 0
    }

</style>