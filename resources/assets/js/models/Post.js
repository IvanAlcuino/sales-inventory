class Post {

    static find(id, then){
        console.log("Post.find id:"+id);
        return axios.get('/pipe/posts/'+id)
          .then(({data}) => { then(data)});
        // return new Promise((resolve, reject) => {
        //     axios['get']('/api/posts/'+2)
        //         .then(response => {
        //             //console.log("result post: "+JSON.stringify(response.data));
        //             this.onSuccess(response.data);                    
        //             resolve(response.data);
        //         })
        //         .catch(error => {
        //             this.onFail(error.response.data);

        //             reject(error.response.data);
        //         });
        // });

    }

    static all(then){
        //return axios.get('/statuses')
        //         .then(({data}) => then(data));
        return axios.get('/pipe/posts/records')
            .then((res) => then(res));
    }
}

export default Post;