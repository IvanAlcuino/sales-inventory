<template> 
    <div >
        <h3>Post List</h3> 
        <div class="">
            <br/>
            <!-- <router-link to="/products/new" class="btn btn-primary btn-sm">New</router-link> -->
            <b-btn @click="openForm()">New Product</b-btn> <br/><br/>
 
            <b-modal ref="modalProductsForm" title="Product"  hide-footer size="lg" >
                    <product-form isModal="true" @added="addToRecord" @updated="updateRecord" @deleted="deleteRecord" @cancel="hideModal"  v-bind="formData"  :pageView="pageView" ></product-form> 
            </b-modal>
            <b-modal ref="modalProductsAddStockForm" title="Add Stock"  hide-footer size="lg" >
                    <product-add-stock-form isModal="true"   @updated="updateCurrentStock"  @cancel="hideStockFormModal"  v-bind="formData"  :pageView="pageView" ></product-add-stock-form> 
            </b-modal>
        </div>
        
        <transition name="fade">
            <div class="form-group col-md-12 alert" :class="errors.length ? 'alert-danger' : 'alert-success'"  role="alert" v-show="message.length"  v-text="message"></div>
        </transition>

        <table class="table table-striped">
            <thead>
                <th class="w-10"></th> 
                <th class="w-50">Name</th>
                <th class="w-10 text-center">Price</th>
                <th class="w-10 text-center">Current Stock</th> 
                <th class="w-10"></th> 
            </thead>
            <tbody>
                <template v-if="!records.length">
                    <tr>
                        <td colspan="5" class="text-center">No Products</td>
                    </tr>
                </template>
                <template v-else>
                    <tr v-for="record in records" :key="record.id">
                        <td>
                            <!-- <img v-bind:src="record.image_url | thumbnail" class="thumbnail" /> -->
                            <div v-bind:style="{ backgroundImage: 'url(' + thumbnail(record.image_url) + ')' }"  class="thumbnail"></div>
                        </td>
                        <td>{{ record.name }}</td>
                        <td class="text-center">{{ record.price | currency }}</td>
                        <td class="text-center">{{ record.current_stock }}</td> 
                        <td>
                            <router-link :to="`/products/${record.id}/edit`" style="font-size:13px">Edit</router-link> &nbsp;
                            <a href="#" @click="openAddStockForm(record)" style="font-size:13px">Add Stock</a>
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>

        <nav aria-label="Page navigation example">
            <ul class="pagination">
                <li v-bind:class="[{disabled: !pagination.prev_page_url}]" class="page-item"><a class="page-link" href="#" @click="fetchRecords(pagination.prev_page_url)">Previous</a></li>
                <li v-bind:class="[{disabled: !pagination.prev_page_url}]" class="page-item disabled"><a class="page-link text-dark" href="#" @click="fetchRecords(pagination.prev_page_url)">{{ pagination.current_page }} of {{ pagination.last_page }} </a></li>
                <li v-bind:class="[{disabled: !pagination.next_page_url}]" class="page-item "><a class="page-link" href="#" @click="fetchRecords(pagination.next_page_url)">Next</a></li>
            </ul>
        </nav>

    </div>
</template>
 

<script>
 
    import Product from '../../models/Product';
    import ProductForm from './ProductForm.vue';   
    import ProductAddStockForm from './ProductAddStockForm.vue';    

    export default {

        components: {ProductForm, ProductAddStockForm},

        props: {  
           setPageView:{
               default:'index',
               type: String,
           },
          
        },

        data(){ 
            return {
                records: [], 
                pagination: {},
                edit: false,
                showModal: false,
                formData: {
                    id:'',
                    name: '',
                    price: '', 
                    current_stock: 0, 
                },  
                message: '',
                errors: '',
                isModal: false,
                pageView: this.setPageView,
            }
        }, 

        created() {   
            //this.formData.id = this.$route.params.rid;
            
            this.fetchRecords(); 
        },
  
        methods: {
            fetchRecords(page_url){
                page_url = page_url || '/pipe/products/records'

                Product.all(page_url, records => { 
                    this.records = records.data.data; 
                    // console.log("posts: "+ JSON.stringify(posts));
                    this.makePagination(records.data.meta, records.data.links);
                });
 
                
            },
            
            openForm(record, pageView){ 
                let user_id = document.getElementById("user_id").innerHTML;
                this.isModal = true;

                if(record === undefined || record === null){ 
                    this.pageView = "add"; 
                    this.formData = {user_id:user_id};

                }else if(pageView == "edit"){
                    this.pageView = "edit";
                    record.user_id = user_id;
                    this.formData = record;

                }else{
                    this.pageView = "show";
                    this.formData = record;
                }
 
                //console.log(JSON.stringify(this.formData)); 
                this.$refs.modalProductsForm.show();
            },

            openAddStockForm(record){ 
                let user_id = document.getElementById("user_id").innerHTML;
                this.isModal = true;
 
                this.pageView = "show";
                this.formData = record; 
 
                //console.log(JSON.stringify(this.formData)); 
                this.$refs.modalProductsAddStockForm.show();
            },

            hideModal () {
                this.$refs.modalProductsForm.hide();
            },
            
            addToRecord(record){
                //console.log("addToPost post:"+JSON.stringify(post)); 
                this.$refs.modalProductsForm.hide();
                this.records.unshift(record); 
                window.scrollTo(0, 0);
            },

            updateRecord(record){
                //console.log("updatePost post:"+JSON.stringify(post)); 
                this.$refs.modalProductsForm.hide();
                this.changePost(record.id, record);  
            },

            deleteRecord(id){
                //console.log("delete post:"+id); 
                this.$refs.modalProductsForm.hide();
                var index = this.records.map(function(x){ return x.id; }).indexOf(id); 
                this.records.splice(index,1);
            },

            changeRecord( id, record ) {
                for (var i in this.records) {
                    if (this.records[i].id == id) {
                        this.records[i].name = record.name;
                        this.records[i].price = record.price;
                        this.records[i].stock = record.stock;
                        break; //Stop this loop, we found it!
                    }
                }
            },


            //Update Stock
            updateCurrentStock(record){
                //console.log("updatePost post:"+JSON.stringify(record)); 
                this.$refs.modalProductsAddStockForm.hide();

                for (var i in this.records) {
                    if (this.records[i].id == record.product_id) {
                        this.records[i].current_stock = record.current_stock; 
                        break; //Stop this loop, we found it!
                    }
                }

                this.message = "Successfully added new stocks.";
                var that = this;
                setTimeout(function() {
                    that.message = "";
                }, 3000);
                
            },
  
            hideStockFormModal() {
                this.$refs.modalProductsAddStockForm.hide();
            },
  
            //End Update Stock
 
            makePagination(meta, links){
                let pagination = {
                    current_page : meta.current_page,
                    last_page : meta.last_page,
                    next_page_url: links.next,
                    prev_page_url: links.prev 
                }

                this.pagination = pagination;
            },

            thumbnail(value){
                if(value){
                    let ar = value.split("."); 
                    return ar[0]+"_s."+ar[1];
                } 
            }

        },

        filters: { 

            currency(cash){
                return "P"+cash.toLocaleString();
            },

            thumbnail(value){
                if(value){
                    let ar = value.split("."); 
                    return ar[0]+"_s."+ar[1];
                } 
            }

        },

    }
</script>


<style scoped>
    .btn-wrapper {
        text-align: right;
        margin-bottom: 20px;
    }
    

    .thumbnail{ 
        height: 80px;
        width: 80px;
        border-radius: 100%;
        background-size:cover;
    }

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

 