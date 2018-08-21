<template>
    
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12"> 

                <b-btn @click="openForm()">Add Post</b-btn> <br/><br/>
 
                <b-modal ref="modalPostForm" title="Post"  hide-footer size="lg" >
                     <post-form isModal="true" @added="addToPost" @updated="updatePost" @deleted="deletePost" @cancel="hideModal"  v-bind="formData"  :pageState="pageState" ></post-form> 
                </b-modal>

               

                <div class="card card-default" v-for="post in posts" :key="post.id">
                    <div class="card-header">
                        <span>{{post.title}} &nbsp; <a href="#" @click="openForm(post, 'edit')">  Edit</a> </span>
                        <span class="float-right">{{  post.created_at | ago | ucFirst }}  </span>
                       
                    </div>

                    <div class="card-body" >
                        <p>{{post.body}}</p>
                        <p>By {{post.user.name}}</p>
                    </div>
                    
                </div>

                <nav aria-label="Page navigation example">
                    <ul class="pagination">
                        <li v-bind:class="[{disabled: !pagination.prev_page_url}]" class="page-item"><a class="page-link" href="#" @click="fetchData(pagination.prev_page_url)">Previous</a></li>
                        <li v-bind:class="[{disabled: !pagination.prev_page_url}]" class="page-item disabled"><a class="page-link text-dark" href="#" @click="fetchData(pagination.prev_page_url)">{{ pagination.current_page }} of {{ pagination.last_page }} </a></li>
                        <li v-bind:class="[{disabled: !pagination.next_page_url}]" class="page-item "><a class="page-link" href="#" @click="fetchData(pagination.next_page_url)">Next</a></li>
                    </ul>
                </nav>

                <!-- <h3>Create Token</h3>
                <passport-clients></passport-clients>
                <passport-authorized-clients></passport-authorized-clients>
                <passport-personal-access-tokens></passport-personal-access-tokens> -->
 

            </div>

            
        </div>
    </div>
</template>

<script>

    import moment from 'moment';
    import Post from '../models/Post'; 
    import PostForm from '../views/posts/PostForm.vue'; 

     
    //pageState = 'add', //add, edit, view

    export default {

        components: {PostForm},

        data(){ 
            return {
                posts: [],
                record_id: '',
                pagination: {},
                edit: false,
                showModal: false,
                formData: {
                    id:'',
                    title: '',
                    body: '',
                    user_id: '',
                }, 
                pageState: 'add',
            }
        },

        filters: {
            ago(date){
                return moment(date + "+08:00").fromNow();
            },

            ucFirst(value){
                return value.charAt(0).toUpperCase() + value.slice(1);
            }

        },

        created() {  
            this.fetchData();
        },
  
        methods: {
            openForm(post, pageState){ 
                 let user_id = document.getElementById("user_id").innerHTML;

                if(post === undefined || post === null){ 
                    this.pageState = "add"; 
                    this.formData = {user_id:user_id};

                }else if(pageState == "edit"){
                    this.pageState = "edit";
                    post.user_id = user_id;
                    this.formData = post;

                }else{
                    this.pageState = "view";
                    this.formData = post;
                }
 
                //console.log(JSON.stringify(this.formData)); 
                this.$refs.modalPostForm.show();
            },

            hideModal () {
                this.$refs.modalPostForm.hide();
            },
            
            addToPost(post){
                //console.log("addToPost post:"+JSON.stringify(post)); 
                this.$refs.modalPostForm.hide();
                this.posts.unshift(post); 
                window.scrollTo(0, 0);
            },

            updatePost(post){
                //console.log("updatePost post:"+JSON.stringify(post)); 
                this.$refs.modalPostForm.hide();
                this.changePost(post.id, post);  
            },

            deletePost(id){
                console.log("delete post:"+id); 
                this.$refs.modalPostForm.hide();
                var index = this.posts.map(function(x){ return x.id; }).indexOf(id); 
                this.posts.splice(index,1);
            },

            changePost( id, post ) {
                for (var i in this.posts) {
                    if (this.posts[i].id == id) {
                        this.posts[i].title = post.title;
                        this.posts[i].body = post.body;
                        break; //Stop this loop, we found it!
                    }
                }
            },

            fetchData(page_url){
                // page_url = page_url || '/api/articles'

                Post.all(posts => { 
                    this.posts = posts.data.data; 
                    // console.log("posts: "+ JSON.stringify(posts));
                    this.makePagination(posts.data.meta, posts.data.links);
                });
 
                
            },
             
            makePagination(meta, links){
                let pagination = {
                    current_page : meta.current_page,
                    last_page : meta.last_page,
                    next_page_url: links.next,
                    prev_page_url: links.prev
                

                }

                this.pagination = pagination;
            },

        }

    }
</script>
