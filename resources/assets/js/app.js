require('./bootstrap'); 
import router from './routes';
import VueCytoscape from 'vue-cytoscape'
import 'vue-cytoscape/dist/vue-cytoscape.css'


//VueCytoscape.use( require('cy-ext') );
//import ext from 'cy-ext';

//VueCytoscape.use( ext );
// import dagre from 'cytoscape-dagre'; 
// VueCytoscape.use( dagre );
 
Vue.use(VueCytoscape)
Vue.component('Home', require('./views/Home.vue'));
 

const app = new Vue({
    el: '#app',
    router
});


 
        
 