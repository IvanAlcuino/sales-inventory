class Product {

    static find(id, then){
        console.log("Product.find id:"+id);
        return axios.get('/pipe/products/'+id)
          .then(({data}) => { then(data)}); 

    }

    static all(url, then){ 
        //'/pipe/products/records'
        return axios.get(url)
            .then((res) => then(res));
    }
}

export default Product;