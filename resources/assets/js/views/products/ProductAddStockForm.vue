<template> 
    <div :class="isModal ? '' : 'card card-default'"> 
  
              
            <form @submit.prevent="onSubmit" @keydown="form.errors.clear()">
                

            <div :class="isModal ? '' : 'card-body'" class="col-md-12 no-gutters"> 
                <div class="row">
                <div :class="isModal ? '' : 'card-body'" class="col-md-7 no-gutters">     
                    <transition name="fade" >
                        <div  class="form-group col-md-8 alert" style="margin-left:3rem" :class="form.errors.length ? 'alert-danger' : 'alert-success'" role="alert" v-show="form.message.length"  v-text="form.message"></div>
                    </transition>

                    <div class="form-group row ">
                        <label for="add-name" class="col-form-label text-md-right col-md-3">Name :</label> 
                        
                        <div class="col-md-8">  
                            <label class="col-form-label" v-if="pageView=='show'"  v-text="form.product_name"></label>
                            <input type="text" id="add-name" v-if="pageView=='edit' || pageView=='add'"  class="form-control "  placeholder="Name" v-model="form.product_name" />
                            <div class="alert alert-danger" v-if="form.errors.has('name')"  v-text="form.errors.get('name')"></div>
                            {{setData}}
                        </div> 
                        
                    </div>

                    <div class="form-group row ">
                        <label for="add-price" class="col-form-label text-md-right col-md-3">Price :</label>  
                        <div class="col-md-4">
                            <label class="col-form-label"  v-if="pageView=='show'"  v-text="form.price"></label>
                            <!-- <input type="text" onkeypress="return isNumberKey(event)" id="add-price" v-if="pageView=='edit' || pageView=='add'"  class="form-control"  placeholder="Name" v-model="form.price" />  -->
                            <b-form-input   type="number"  id="add-price" v-if="pageView=='edit' || pageView=='add'"  class="form-control"  placeholder="Name" v-model="form.price"></b-form-input>
                            <div class="alert alert-danger" v-if="form.errors.has('price')"  v-text="form.errors.get('price')"></div> 
                        </div>
                    </div>

                    <div class="form-group row no-gutter">
                        <label for="add-price" class="col-form-label text-md-right col-md-3">Current Stock :</label>  
                        <div class="col-md-4">
                            <label class="col-form-label"  v-if="pageView=='show'"  v-text="form.current_stock"></label>
                            <!-- <input type="text" onkeypress="return isNumberKey(event)" id="add-price" v-if="pageView=='edit' || pageView=='add'"  class="form-control"  placeholder="Name" v-model="form.price" />  -->
                            <b-form-input   type="number"  id="add-price" v-if="pageView=='edit' || pageView=='add'"  class="form-control"  placeholder="Name" v-model="form.current_stock"></b-form-input>
                            <div class="alert alert-danger" v-if="form.errors.has('price')"  v-text="form.errors.get('price')"></div> 
                        </div>
                    </div>

                    <div class="form-group row no-gutter">
                        <label for="add-price" class="col-form-label text-md-right col-md-3">Add Qty:</label>  
                        <div class="col-md-4"> 
                            <!-- <input type="text" onkeypress="return isNumberKey(event)" id="add-price" v-if="pageView=='edit' || pageView=='add'"  class="form-control"  placeholder="Name" v-model="form.price" />  -->
                            <b-form-input   type="number"  id="add-qty"    class="form-control"  placeholder="Qty" v-model="form.added_qty"></b-form-input>
                            <div class="alert alert-danger" v-if="form.errors.has('price')"  v-text="form.errors.get('price')"></div> 
                        </div>
                    </div>
  
                    

                    <div class="form-group row" >
                        <label for="add-image_url" class="col-form-label text-md-right col-md-3"></label> 
                        <div class="col-md-8">
                            <button class="btn btn-primary" :disabled="form.errors.any()"  >{{btnName}}</button>   
                            <button class="btn btn-default" @click.prevent="onCancel()" >Cancel</button> 
                        </div>
                    </div>
                </div> 
                <div class="col-md-3 no-gutters">
                    <div class="form-group col-md-0 row center" v-if="form.image_url">  
                            <img v-bind:src="form.image_url" class="" style="width:200px;"  /> 
                    </div> 
                </div> 

                </div>   
            </div>
            </form>    
        
    </div>    
</template>

<script>
    import Product from '../../models/Product';

    export default{
        props: ['isModal', 'id', 'name', 'price', 'image_url','current_stock', 'pageView'],

        data(){
            return{
                form: new Form({product_id:'', product_name:'', price:'',  image_url:'', current_stock:0, added_qty:0, message: '', image: null}),
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
                
                 
                    this.form
                    .post('/pipe/inventory/addStock')
                    .then(result => {
                        if(result.data=='success'){ 
                            console.log('result:'+JSON.stringify(result));
                            this.form.current_stock = result.current_stock;
                            if(this.isModal){                                
                                this.$emit('updated', this.form);
                                this.reset();
                            }     
                        }else{
                            this.form.message = "Error while saving.";
                        }

                    });
             
                
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
 
               
                if(this.isModal){  
                    this.form.product_id = this.id;
                    this.form.product_name = this.name;
                    this.form.price = this.price;
                    this.form.image_url = this.image_url;
                    this.form.current_stock = this.current_stock;
  
                    this.form.user_id = this.user_id; 
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

    .row.no-gutters {
        margin-right: 0;
        margin-left: 0; 
    }

</style>