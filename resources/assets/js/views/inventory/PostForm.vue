<template>
    <div :class="isModal ? '' : 'card card-default'">
        
        <form @submit.prevent="onSubmit" @keydown="form.errors.clear()">
             

            <div :class="isModal ? '' : 'card-body'">
                <div class="alert" :class="form.errors.length ? 'alert-danger' : 'alert-success'" role="alert" v-show="form.message.length"  v-text="form.message"></div>
                <div class="form-group">
                    <label for="add-title">Title :</label> 
                    <span class="" v-if="pageView=='show'"  v-text="form.title"></span>
                    <input type="text" id="add-title" v-if="pageView=='edit' || pageView=='add'"  class="form-control"  placeholder="Title" v-model="form.title" /> 
                    <span class="" v-if="form.errors.has('title')"  v-text="form.errors.get('title')"></span>
                    {{setData}}
                </div>
                <div class="form-group">
                    <label for="add-body">Body :</label> 
                    <span class="" v-if="pageView=='show'"  v-text="form.body"></span>
                    <textarea id="add-body"  v-if="pageView=='edit' || pageView=='add'" class="form-control" rows="10" cols="80" placeholder="Body" v-model="form.body" ></textarea>
                    <span class="" v-if="form.errors.has('body')"  v-text="form.errors.get('body')"></span>
                    {{setData}}
                </div>

                <div class="form-group">
                    <button class="btn btn-primary" :disabled="form.errors.any()" v-show="pageView=='edit' || pageView=='add'">{{btnName}}</button> 
                    <router-link :to="`/posts/${form.id}/edit`" class="btn btn-secondary" v-show="pageView=='show'"  >Edit</router-link>    
                    <button class="btn btn-danger" @click.prevent="onDelete(form.id)" v-show="pageView=='edit'">Delete</button> 
                    <button class="btn btn-default" @click.prevent="onCancel()" >Cancel</button> 
                </div>
            </div> 

        </form>    
    </div>
</template>

<script>
    import Post from '../../models/Post';

    export default{
        props: ['isModal', 'id', 'title', 'body', 'user_id', 'pageView'],

        data(){
            return{
                form: new Form({id:'', title:'', body:'', user_id:'', message: ''}),
                btnName: 'Save',
                post:{},
                rid: this.$route.params.rid,
            }
        },

       

        methods: {
            fetchRecord(id){
 
                Post.find(id, result => {   
                    //console.log("before post: "+JSON.stringify(result));
                    let post = result.data[0];  
                    for(let f in post){
                        this.form[f] = post[f]; 
                    }    
                    //console.log("after post: "+JSON.stringify(this.form));
                });

                 
                
            },
            onSubmit(){ 
                
                if(this.pageView == "add"){
                    this.form
                    .post('/pipe/posts')
                    .then(post => {
                        if(this.isModal){
                            this.$emit('added', post.data);
                            this.reset();
                        }    

                        this.$router.push('/posts');
                        
                    });
                }else{
                    this.form
                    .put('/pipe/posts')
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
                    .delete('/pipe/posts/'+id)
                    .then(post => {
                        if(this.isModal){
                            this.$emit('deleted', post.data.id)
                        }else{
                           console.log("delete: "+id); 
                           this.$router.push('/posts'); 
                        }
                    });
            },

            onCancel(){  
                if(this.isModal){
                    this.$emit('cancel');
                }else{
                    this.$router.push('/pipe/posts');
                }

            }, 

            reset(){  
                
                this.form.reset();
            }, 

            getProps() {
                this.form.body = this.body;
                // let props = this.$vnode.elm.attributes;
                // Object.keys(props).forEach(key => {
                // this.$set(this.props, props[key].name, props[key].nodeValue)
                // });
            }

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
                console.log("title: "+this.title); 
                console.log("body: "+this.body); 
                console.log("user_id: "+this.user_id); 
                console.log("isModal: "+this.isModal);  

                if(this.isModal == true){ 
                    this.form.id = this.id;
                    this.form.title = this.title;
                    this.form.body = this.body;
                    this.form.user_id = this.user_id;
                }else{
                    //this.rid = this.$route.params.rid;
                    if(this.pageView == 'show' || this.pageView == 'edit')
                        this.fetchRecord(this.rid);

                }

                
            }
        },
         
        mounted() {
            // console.log("mounted - this.body: "+this.body);
            // this.getProps();
            //this.form.body = this.body;

            // if(this.pageView == "add"){
            //     this.form.reset();
            // }else{
            //     this.form.data = this.data;
            // }
            
        }

        
    }

</script>