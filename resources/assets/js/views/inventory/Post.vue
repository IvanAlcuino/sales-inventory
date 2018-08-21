<template> 
    <div class=""> 

        <router-link :to="`/posts/add`" class="btn btn-secondary"> Add Post</router-link>
        <a href="/posts/add" class="btn btn-secondary">Add Post - redirect</a> <br/><br/> 
        <b-btn @click="openForm()">Add Post - Dialog</b-btn> <br/><br/>

        <b-modal ref="modalPostForm" title="Post"  hide-footer size="lg" >
                <post-form :isModal="isModal" @added="addToPost" @updated="updatePost" @deleted="deletePost" @cancel="hideModal"  v-bind="formData"  :pageView="pageView" ></post-form> 
        </b-modal>

         
        <div class="card card-default" v-for="post in posts" :key="post.id">
            <div class="card-header">
                <span> <router-link :to="`/posts/${post.id}`" >{{post.title}} </router-link> &nbsp; <router-link :to="`/posts/${post.id}/edit`" >edit</router-link> <a href="#" @click="openForm(post, 'edit')"> Dialog-Edit</a>  </span>
                <span class="float-right">{{  post.created_at | ago | ucFirst }}  </span>
                
            </div>

            <div class="card-body" >
                <p>{{post.body}}</p>
                <p>By {{post.user.name}}</p>
            </div>
            
        </div>

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

    import moment from 'moment';
    import Post from '../../models/Post';
    import PostForm from './PostForm.vue';   
    //pageView = 'add', //add, edit, view

    export default {

        components: {PostForm},

        props: {  
           setPageView:{
               default:'index',
               type: String,
           },
          
        },

        data(){ 
            return {
                posts: [], 
                pagination: {},
                edit: false,
                showModal: false,
                formData: {
                    id:'',
                    title: '',
                    body: '',
                    user_id: '',
                },  
                isModal: false,
                pageView: this.setPageView,
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
            this.formData.id = this.$route.params.id;
            //console.log("id:" + this.formData.id);

            if(this.pageView == 'show'){
               

            }
            this.fetchRecords();
        },
  
        methods: {
            openForm(post, pageView){ 
                let user_id = document.getElementById("user_id").innerHTML;
                this.isModal = true;

                if(post === undefined || post === null){ 
                    this.pageView = "add"; 
                    this.formData = {user_id:user_id};

                }else if(pageView == "edit"){
                    this.pageView = "edit";
                    post.user_id = user_id;
                    this.formData = post;

                }else{
                    this.pageView = "view";
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
                //console.log("delete post:"+id); 
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

            fetchRecords(page_url){
                // page_url = page_url || '/articles'

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
