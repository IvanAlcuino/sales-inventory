class Transaction {

      
    static find(id, then){
        console.log("User.find id:"+id);
        return axios.get('/pipe/transactions/'+id)
          .then(({data}) => { then(data)}); 

    }

    static all(url, then){  
        return axios.get(url)
            .then((res) => then(res));
    }
}

export default Transaction;