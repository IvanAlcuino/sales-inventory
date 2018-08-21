<template> 
    <div :class="isModal ? '' : 'card card-default'"> 
  
              
            <form @submit.prevent="onSubmit" @keydown="form.errors.clear()">
                

                <div :class="isModal ? '' : 'card-body'">
                    <div class="form-group">
                        <h3 class="card-title">Transaction</h3> 
                    </div>  
                    <div class="form-group">
                        <label for="add-id">Trans. ID:</label> 
                        <span class="" v-if="pageView=='show'"  v-text="form.id"></span>
                        {{setData}} 
                    </div>

                    <div class="form-group">
                        <label for="add-name">Member Name :</label> 
                        <span class="" v-if="pageView=='show'"  v-text="form.member_name"></span>  
                    </div>

                    <div class="form-group">
                        <label for="add-amount">Amount :</label> 
                        <span class="" v-if="pageView=='show'"  v-text="form.amount"></span> 
                    </div>

                     
                    <div class="form-group">
                        <button class="btn btn-primary" :disabled="form.errors.any()" v-show="pageView=='edit' || pageView=='add'">{{btnName}}</button>  
                        <button class="btn btn-default" @click.prevent="onCancel()" >Cancel</button> 
                    </div>
                </div> 

            </form>    
    
    </div>    
</template>

<script>
    import Transaction from '../../models/Transaction';

    export default{
        props: ['isModal', 'id', 'member_id', 'member_name', 'amount', 'pageView'],

        data(){
            return{
                form: new Form({id:'', member_name:'', amount:'',   message: ''}),
                btnName: 'Save', 
                rid: this.$route.params.rid,
            }
        },

       

        methods: {
            fetchRecord(id){
 
                Transaction.find(id, results => {   
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
                    .post('/pipe/transactions')
                    .then(post => {
                        if(this.isModal){
                            this.$emit('added', post.data);
                            this.reset();
                        }    

                        this.$router.push('/transactions');
                        
                    });
                }else{
                    this.form
                    .put('/pipe/transactions')
                    .then(post => {
                        if(this.isModal){
                            this.$emit('updated', post.data);
                            this.reset();
                        }  

                        this.form.message = "Saved successfully.";
                    });
                }
                 
                
            },

            onDelete(id){  
                 this.form
                    .delete('/pipe/transactions/'+id)
                    .then(post => {
                        if(this.isModal){
                            this.$emit('deleted', post.data.id)
                        }else{
                           console.log("delete: "+id); 
                           this.$router.push('/transactions'); 
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