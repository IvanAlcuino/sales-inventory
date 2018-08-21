<template> 
    <div :class="isModal ? '' : 'card card-default card-page'">   
        <form v-on:submit.prevent> 
            <div :class="isModal ? '' : 'card-body'">
                <div class="form-group row"  >
                    <label for="" class="col-md-2 ">&nbsp;</label>
                    <h3 class="card-title">&nbsp;&nbsp;&nbsp; Add Transaction</h3> 
                </div>   

                <transition name="fade">
                   <div class="form-group col-md-8 alert" style="margin-left:3rem" :class="form.errors.length ? 'alert-danger' : 'alert-success'"  role="alert" v-show="form.message.length"  v-text="form.message"></div>
                </transition>
                <div class="form-group row">
                        <label for="full_name" class="col-md-2 col-form-label text-md-right">Member Name :</label>

                        <div class="col-md-4 autocomplete">
                            <!-- <input id="full_name" type="text" class="form-control " name="member_name"   placeholder="Member Name" v-model="form.member" required autofocus>  -->
                                
                            <input type="text" placeholder="Search Member"   class="form-control" 
                                v-model="find_member"  
                                @input="autoComplete"
                                @keydown.down="onArrowDown"
                                @keydown.up="onArrowUp"
                                @keydown.enter="onEnter"
                            >
                            <div class="panel-footer"   v-show="m_isOpen">
                                <ul id="autocomplete-found_members" class="list-group autocomplete-results"  >
                                    <li
                                        class="list-group-item loading"
                                        v-if="m_isLoading">
                                        Loading members...
                                    </li>
                                    
                                    <li   
                                        v-else
                                        v-for="(m, i) in found_members"
                                        :key="i"
                                        @click="setResult(m)"
                                        class="list-group-item autocomplete-result"
                                        :class="{ 'is-active': i === m_arrowCounter }"
                                    >
                                    {{ m.name }}  
                                    </li>
                                </ul>
                            </div> 
                            <div class="alert alert-danger" v-if="form.errors.has('member')"  v-text="form.errors.get('member')"></div> 
                        
                        </div> 
                </div>

                <div class="form-group row autocomplete">
                    <label for="add-product" class="col-md-2 col-form-label text-md-right">Product :</label>  
                    <div class="col-md-4"> 
                        <input type="text" placeholder="Search Product"   class="form-control" 
                            v-model="find_product"  
                            @input="p_autoComplete"
                            @keydown.down="p_onArrowDown"
                            @keydown.up="p_onArrowUp"
                            @keydown.enter="p_onEnter"
                        >
                        <div class="panel-footer"   v-show="p_isOpen">
                            <ul id="autocomplete-found_products" class="list-group autocomplete-results"  >
                                <li
                                    class="list-group-item loading"
                                    v-if="p_isLoading">
                                    Loading products...
                                </li>
                                
                                <li   
                                    v-else
                                    v-for="(p, i) in found_products"
                                    :key="i"
                                    @click="p_setResult(p)"
                                    class="list-group-item autocomplete-result"
                                    :class="{ 'is-active': i === p_arrowCounter }"
                                >
                                {{ p.name }}  
                                </li>
                            </ul>
                        </div> 
                        <div class="alert alert-danger" v-if="form.errors.has('product')"  v-text="form.errors.get('product')"></div> 
                    </div>
                        
                </div>

                <div class="form-group row">
                    <label for="full_name" class="col-md-2 col-form-label text-md-right">Qty :</label>
                    <div class="col-md-1">     
                        <b-form-input   type="number" id="add-qty"  v-model="input_qty" onClick="this.select();" ></b-form-input>
                    </div>   
                    <div class="col-md-5">
                        <button class="btn btn-primary btn-xl" :disabled="!product.name"   @click="addProduct()">&nbsp;&nbsp; Add Product &nbsp;&nbsp;</button>
                    </div>
                </div>

                <div class="form-group row"> 
                </div>

                <div class="form-group row">
                    <div class="col-md-1"> &nbsp; </div>
                    <div class="col-md-10">
                        <table class="table table-striped">
                            <thead class="thead-default">
                                <th class="w-50">Product</th>
                                <th class="text-right w-15">Price</th>
                                <th class="text-center w-15">Qty</th> 
                                <th class="text-right w-15">Amount</th> 
                                <th class="text-center" width="10">&nbsp;</th>
                            </thead>
                            <tbody> 
                                <template >
                                    <tr v-for="(product, index) in form.products" :key="product.id"> 
                                        <td> {{ product.name }} </td> 
                                        <td class="text-right">{{ product.price | currency }}</td> 
                                        <td class="text-center">{{ product.qty }}</td> 
                                        <td class="text-right">{{ product.amount | currency }}</td> 
                                        <td  >
                                             <span class="glyphicon" style="cursor:pointer" @click="removeProduct(product, index)"> 
                                                <i class="fa fa-trash-o fa-2" aria-hidden="true"></i>
                                            </span> 
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="form-group row">
                    <label   class="col-md-9 col-form-label text-md-right">Total Amount :</label>
                    <div class="col-md-2-pd-12">    
                        <input type="text"  class="form-control text-right" v-model="form.total_amount" disabled readonly />
                    </div>   
                </div>

                <div class="form-group row">
                    <label   class="col-md-9 col-form-label text-md-right">Cash :</label>
                    <div class="col-md-2-pd-12">     
                         <b-form-input   type="number" class="form-control text-right"  id="add-cash-input" onClick="this.select();"  v-model="input_cash" @input="computeChange"   ></b-form-input>
                         <div class="alert alert-danger small" v-if="form.errors.has('input_cash')"  v-text="form.errors.get('input_cash')" style="padding:0.75rem 0.45rem"></div> 
                    </div>   
                </div> 

                <div class="form-group row">
                    <label   class="col-md-9 col-form-label text-md-right">Change :</label>
                    <div class="col-md-2-pd-12">    
                        <input type="text"  class="form-control text-right" v-model="cash_change" disabled readonly />
                        <div class="alert alert-danger" v-if="form.errors.has('cash_change')"  v-text="form.errors.get('cash_change')"></div> 
                    </div>
                </div>   

                <div class="form-group row">
                    <label   class="col-md-9 col-form-label text-md-right"></label>
                    <div class="col-md-2-pd-12">
                        <button class="btn btn-primary" @click="saveTransaction()">Save Transaction</button>
                    </div>
                </div>      

            </div> 

        </form>    
    
    </div>    
</template>

<script>
    import Product from '../../models/Product';

    export default{
        props: ['isModal', 'id', 'member_id', 'member_name', 'amount', 'pageView'],

        data(){
            return{
                product: {},
                products: [],
                form: new Form({member_id:'', member_name:'', total_amount: 0, message: '', products:[]}), 
                rid: this.$route.params.rid,
                input_qty: '1',
                input_cash: '0',
                cash_change: '0',

                find_member: '',
                found_members: [], 
                selected_member_id: '',
                m_arrowCounter: -1,
                m_isOpen: false,
                m_isLoading: false,

                find_product: '',
                found_products: [], 
                selected_product_id: '',
                p_arrowCounter: -1,
                p_isOpen: false,
                p_isLoading: false,

            }
        },

       

        methods: {
            fetchRecord(id){
                  
                Product.find(id, results => {   
                    //console.log("before post: "+JSON.stringify(result));
                    let result = results.data[0];  
                    for(let f in result){
                        this.products[f] = result[f]; 
                    }    
                    //console.log("after post: "+JSON.stringify(this.form));
                });
 
            },  

            saveTransaction(){  

                if(this.checkTransaction()==0){  
                    this.form
                    .post('/pipe/transactions')
                    .then(result => {      
                        if(result.data=='success'){
                            this.find_member = "";
                            this.cash_change = "0";
                            this.input_cash = "0";
                            this.form = new Form({member_id:'', member_name:'', total_amount: 0, message: '', products:[]});
                            //this.form.reset();
                            this.form.message = "Transaction successfully saved."

                            var that = this;
                            setTimeout(function() {
                                that.form.message = "";
                            }, 3000);
                            
                        }
                        
                    });
                }  
                
            }, 

            checkTransaction(){
                let errors = {};

                if(this.input_cash==0 || Number(this.input_cash < this.form.total_amount)){
                    errors['input_cash'] = ["Cash required."]  
                }
                    

                if(Number(this.cash_change) < 0){
                    errors['input_cash'] = ["Please input cash greater or equal to the total amount."]  
                }

                if(Number(this.form.total_amount)==0){
                    errors['product'] = ["Please add product."] 
                }
 
                if(this.find_member.length == 0){
                    errors['member'] = ["Please input a member."] 
                }
                   
                 
                this.form.errors.record(errors);

                //console.log(errors)
                return Object.keys(errors).length > 0;
                

            },

            reset(){  
                
                this.form.reset();
                this.found_members = [];
                this.input_cash = '';
                this.cash_change = '';
                this.m_arrowCounter = -1;
                this.find_member = '';
            }, 

            //------ Members Autocomplate
            autoComplete(){
                this.found_members = [];
                if(this.find_member.length > 1){
                    this.m_isLoading = true; 
                    axios.get('/pipe/user/search',{params: {q: this.find_member}}).then(response => {    
                        this.found_members = response.data;
                        this.m_isOpen = true;
                        this.m_isLoading = false;

                    });
                }
            },
            setResult(result) {
                
                this.find_member = result.name;
                this.form.member_id = result.id; 
                this.form.member_name = this.find_member
                this.selected_member_id = result.id; 
                this.m_arrowCounter = -1;
                this.m_isOpen = false; 

                if(this.form.errors.has("member")){                    
                    this.form.errors.removeField("member");
                }
                
            },
            onArrowDown(evt) {
                //alert("onArrowDown.. ");
                //alert("m_arrowCounter:"+this.m_arrowCounter+" this.found_members.length:"+this.found_members.length);
                if (this.m_arrowCounter < this.found_members.length) { 
                    this.m_arrowCounter = this.m_arrowCounter + 1;
                }
            },
            onArrowUp() {
                if (this.m_arrowCounter > 0) {
                this.m_arrowCounter = this.m_arrowCounter -1;
                }
            },
            onEnter() {
                this.find_member = this.found_members[this.m_arrowCounter].name;
                this.m_isOpen = false;
                this.m_arrowCounter = -1;
            },
            handleClickOutside(evt) {
                if (!this.$el.contains(evt.target)) {
                this.m_isOpen = false;
                this.m_arrowCounter = -1;
                }
            },
            //------- END of Members autocomplate

            //------ Products Autocomplate
            p_autoComplete(){
                this.found_products = [];
                if(this.find_product.length > 1){
                    this.p_isLoading = true; 
                    axios.get('/pipe/products/search',{params: {q: this.find_product}}).then(response => {    
                        this.found_products = response.data;
                        this.p_isOpen = true;
                        this.p_isLoading = false;

                        if(this.form.errors.has("product")){                    
                            this.form.errors.removeField("product");
                        }

                    });
                }
            },
            p_setResult(result) {
                
                this.find_product = result.name;
                this.selected_product_id = result.id; 
                this.product=result;
                this.p_arrowCounter = -1;
                this.p_isOpen = false; 

                //alert(result.name)
                
            },
            p_onArrowDown(evt) { 
                if (this.p_arrowCounter < this.found_products.length) { 
                    this.p_arrowCounter = this.p_arrowCounter + 1;
                }
            },
            p_onArrowUp() {
                if (this.p_arrowCounter > 0) {
                this.p_arrowCounter = this.p_arrowCounter -1;
                }
            },
            p_onEnter() {
                this.find_product = this.found_products[this.p_arrowCounter].name;  
                this.product=this.found_products[this.p_arrowCounter];
                this.p_isOpen = false;
                this.p_arrowCounter = -1;
                
            },
            p_handleClickOutside(evt) {
                if (!this.$el.contains(evt.target)) {
                this.p_isOpen = false;
                this.p_arrowCounter = -1;
                }
            },
            //------- END of Products autocomplate 

            addProduct(){ 
                //alert("addProduct")
                this.product.qty = this.input_qty; 
                this.form.products.push(this.product);
                this.product.amount = Number(this.input_qty) * Number(this.product.price);
                this.form.total_amount = Number(this.form.total_amount) + Number(this.product.amount);

                this.find_product = "";
                this.input_qty="1";
                this.product = {};  

                if(this.form.errors.has("product")){                    
                    this.form.errors.removeField("product");
                }
            
            },

            removeProduct(product, index){
                 
                this.form.total_amount = Number(this.form.total_amount) - Number(product.amount);
                this.form.products.splice(index, 1); 
               
            },

            computeChange(){
                this.cash_change = (this.input_cash - this.form.total_amount).toLocaleString();

                if(Number(this.cash_change) >=0){
                    if(this.form.errors.has("input_cash")){                    
                        this.form.errors.removeField("input_cash");
                    }
                }    
            },

          

        },

        filters: { 

            currency(cash){
                return "P"+cash.toLocaleString();
            },

            ucFirst(value){
                return value.charAt(0).toUpperCase() + value.slice(1);
            }

        },

        created(){
            //this.fetchRecord();     
            //console.log("errors.length: "+this.form.errors.length);
            //this.checkTransaction();
        },

        computed: {
             
            setData(){
               
                
            }
        },
         
        mounted() { 
            
        }

        
    }

</script>


<style>
    .autocomplete {
        position: relative;
    } 

    .autocomplete-result.is-active,
    .autocomplete-result:hover {
        background-color: #4AAE9B;
        color: white;
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
