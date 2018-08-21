class Member {

    static getDownlineMembers(id, then){
        console.log("User.find id:"+id);
        return axios.get('/pipe/members/getDownlineMembers/'+id)
            .then((res) => then(res));
          //.then(({data}) => { then(data)}); 

    }

    static find(id, then){
        console.log("User.find id:"+id);
        return axios.get('/pipe/members/'+id)
          .then(({data}) => { then(data)}); 

    }

    static all(url, then){  
        return axios.get(url)
            .then((res) => then(res));
    }
}

export default Member;