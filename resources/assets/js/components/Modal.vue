<template>
    <transition name="modal">
        <div class="modal-mask">
            <div class="modal-wrapper">
                <div class="modal" tabindex="-1" role="dialog">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header"> 
                                <slot name="header"> 
                                    <h5 class="modal-title">Modal title</h5>
                                </slot>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            
                            <div class="modal-body">
                                <slot name="body">
                                    default body
                                </slot> 
                            </div> 
                            
                            <div class="modal-footer">
                                <slot name="footer">
                                    <button type="button" class="btn btn-primary" @click="$emit('close')">Save changes</button>
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                </slot>  
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </transition>
</template>    

<script>
    export default{

        data(){
            return{
                showModal: false
            }
        },
        
        methods: {
             
        }
    }

</script>