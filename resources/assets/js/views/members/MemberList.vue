<template>
    <div >
        <div class="btn-wrapper">
            <router-link to="/members/add" class="btn btn-primary btn-sm">Register New Member</router-link>
        </div>
        <table class="table table-striped">
            <thead>
                <th>Name</th>
                <th>Phone No</th>
                <th>Address</th>
                <th>City</th>
                <th>Province</th>
                <th>Action</th>
            </thead>
            <tbody>
                <template v-if="!records.length">
                    <tr>
                        <td colspan="5" class="text-center">No members available</td>
                    </tr>
                </template>
                <template v-else>
                    <tr v-for="record in records" :key="record.id">
                        <td><router-link :to="`/members/${record.id}`">{{ record.name }}</router-link></td> 
                        <td>{{ record.mobile_no }}</td>
                        <td>{{ record.address }}</td> 
                        <td>{{ record.city }}</td> 
                        <td>{{ record.province }}</td>
                        <td>
                            <router-link :to="`/members/${record.id}/edit`">Edit</router-link>
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
 
    import Member from '../../models/Member';
    import MemberForm from './MemberForm.vue';    

    export default {

        components: {MemberForm},

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
                    stock: 0, 
                },  
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
                page_url = page_url || '/pipe/members'

                Member.all(page_url, records => { 
                    this.records = records.data.data; 
                    //console.log("Members: "+ JSON.stringify(records));
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
                    this.formData = record;

                }else{
                    this.pageView = "view";
                    this.formData = record;
                }
 
                //console.log(JSON.stringify(this.formData)); 
                this.$refs.modalProductsForm.show();
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
                        // this.records[i].price = record.price;
                        // this.records[i].stock = record.stock;
                        break; //Stop this loop, we found it!
                    }
                }
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


<style scoped>
.table{
    background-color: #fff;
}
.btn-wrapper {
    
    margin-bottom: 20px;
}
</style>



