<template>
     <div class="container">
         <div class="row justify-content-center">
            <div class="col-md-12">  
                <!-- <span> Membership Node</span> 
                <div style="background-image: url('/pages/Body-short.jpg');  width: 100%; height: 1500px; padding-top: 9.5em;  padding-left: 20em;">
                </div> -->
                    <div id="content-header" style="display:none" >
                        <div><label style="width:110px; display: inline-block;">Source ID</label> <label style="width:110px; display:inline-block;">ID</label> <label style="width:100px; display: inline-block;">Name</label></div>
                        <input type="text" name="sid" id="sid" placeholder="Source ID" style="width:100px" /> 
                        <input type="text" name="mid" id="mid" placeholder="ID" style="width:100px" />
                        <input type="text" name="mname" id="mname" placeholder="Name" style="width:100px"/> 
                        <button @click="addNode()">Add Node</button>
                    </div>
                    <div>
                        <cytoscape :config="config" :preConfig="preConfig"  style="width: 100%; height: 700px"/>
                    </div>  
                
            </div>
        </div>
    </div>
        

        
</template>

<script> 
import Member from '../../models/Member'; 
import dagre from 'cytoscape-dagre';  

export default {
   
        data(){ 
            return {
                records: [], 
                user_id: '',
                user_name: '', 
                donwline: [], 
                config: {  
                    boxSelectionEnabled: false,
                    autounselectify: true, 
                    style: [
                        {
                            selector: 'node',
                            style: {
                                'content': 'data(id)', 
                                'text-valign': 'center',
                                'text-halign': 'right',
                                'label': 'data(name)',
                                'font-size': 6,  
                                'opacity': 1,
                                'background-width': '100',
                                'background-height': '100', 
                                'text-wrap': 'wrap',
                                'text-margin-x': '2',
                            }
                        }, 
                        {
                            selector: 'edge',
                            style: {
                                'curve-style': 'bezier',
                                'width': 2,
                                'line-color': '#9dbaea',
                                'target-arrow-color': '#9dbaea'
                            }
                        }
                        
                    ],  
                }     
            }
        }, 

        created() {    
            this.fetchRecords();    
            //this.cyUpdate(); 

        },
  
        methods: {
            fetchRecords(){ 
                this.user_id = document.getElementById("user_id").innerHTML.trim();
                this.user_name = document.getElementById("user_name").innerHTML.trim();
               
                this.linkDownlineMembers(this.user_id);

                              
            }, 

            linkDownlineMembers(user_id){  
                
                Member.find(user_id, result => {   
                   
                    //console.log("Member find result: "+JSON.stringify(result));
                    let user = result.data[0];
                    //console.log("User: "+JSON.stringify(user));

                    Member.getDownlineMembers(this.user_id, result2 => {   
                        //console.log("getDownlineMembers: "+JSON.stringify(result));
                        //this.donwline = result.data;   
                        //link the donwline member from the root
                        let members2 = result2.data.data;
                        if(members2.length<1) return;
                        this.LinkDataNode(user, members2);

                        for(let i=0; i<members2.length; i++){
                            //get the donwlind of a member and link.
                            Member.getDownlineMembers(members2[i].id, result3 => {   
                                //console.log("getDownlineMembers 3rd Level: "+JSON.stringify(result3));
                                let members3 = result3.data.data;
                                if(members3.length>0){
                                    this.LinkDataNode(members2[i], members3);

                                    for(let i2=0; i2<members3.length; i2++){
                                        //get the donwlind of a member and link.
                                        //console.log("GET 4th level userid:"+members3[i2].id);
                                        Member.getDownlineMembers(members3[i2].id, result4 => {   
                                            //console.log("getDownlineMembers 4th Level: "+JSON.stringify(result4));
                                            let members4 = result4.data.data;
                                            if(members4.length>0){
                                                this.LinkDataNode(members3[i2], members4); 

                                                for(let i3=0; i3<members4.length; i3++){
                                                    //get the donwlind of a member and link.
                                                    //console.log("GET 5th level userid:"+members4[i3].id);
                                                    Member.getDownlineMembers(members4[i3].id, result5 => {   
                                                        //console.log("getDownlineMembers 4th Level: "+JSON.stringify(result5));
                                                        let members5 = result5.data.data;
                                                        if(members5.length>0){
                                                            this.LinkDataNode(members4[i3], members5); 

                                                            for(let i4=0; i4<members5.length; i4++){
                                                                //get the donwlind of a member and link.
                                                                //console.log("GET 5th level userid:"+members5[i4].id);
                                                                Member.getDownlineMembers(members5[i4].id, result6 => {   
                                                                    //console.log("getDownlineMembers 4th Level: "+JSON.stringify(result6));
                                                                    let members6 = result6.data.data;
                                                                    if(members6.length>0){
                                                                        this.LinkDataNode(members5[i4], members6); 

                                                                        this.renderNode();
                                                                    }else{
                                                                        this.renderNode();
                                                                    }    
                                                                });
                                                            }
                                                        }else{
                                                            this.renderNode();
                                                        }    
                                                    });
                                                }
                                            }else{
                                                this.renderNode();
                                            }    
                                        });
                                    }

                                }else{
                                    this.renderNode();
                                }    
                            });
                        }

                    });
                   
                });

                
                
            }, 

            LinkDataNode(user, members){ 
                //console.log("LinkDataNode user: "+JSON.stringify(user));
                //console.log("LinkDataNode members: "+JSON.stringify(members));
                let nodes = [];
                let links = []; 

                //root node
                let i=0;
                let node = {
                    data: { id: user.id, name: user.name + '\n(P' + user.monthly_sales.toLocaleString() + ')' }
                };

                nodes.push(node);

                for(let i=0; i<members.length; i++){
                    let node = {
                        data: { id: members[i].id, name : members[i].first_name + '\n(P' + members[i].monthly_sales.toLocaleString() + ')' }
                    };

                    let link = {
                        data: { source: user.id, target: members[i].id }
                    };
                    
                    nodes.push(node);
                    links.push(link);
                }
 
                // console.log(nodes); 
                // console.log(links); 
                 
                
                // update the cytoscape instance
                this.$cytoscape.instance.then(cy => { 
                    cy.add(nodes)
                    cy.add(links)   

                    for(let i=0; i<nodes.length; i++){
                        //console.log("node_id:"+nodes[i].data.id);
                        if(i==0){
                            if(user.image_url !== null){
                                cy.elements('node#'+nodes[i].data.id).style({
                                    'background-image': '/storage/images/users/'+nodes[i].data.id+'/'+nodes[i].data.id+'_s.jpg',
                                    'background-fit': 'cover cover'
                                    })
                            }else if(members[i].image_url !== null){
                                cy.elements('node#'+nodes[i].data.id).style({
                                    'background-image': '/storage/images/users/'+nodes[i].data.id+'/'+nodes[i].data.id+'_s.jpg',
                                    'background-fit': 'cover cover'
                                    })
                            } 
                        }
                         
                    }
                    
                    


                    cy.on('tap', 'node', function (event) {
                        const data = event.target.data()
                        console.log(JSON.stringify(data));
                    })

                    // cy.stylesheet().selector('node').selector('#1').css({
                    //     'background-image': 'https://farm2.staticflickr.com/1261/1413379559_412a540d29_b.jpg'
                    // })

                    //.selector('node')
                    //cy.elements('node#1').style('background-color', 'magenta')
                    //cy.elements('node#1').style('background-image', 'https://farm2.staticflickr.com/1261/1413379559_412a540d29_b.jpg')

                    //cy.nodes()[0].css("background-image", "url(https://farm8.staticflickr.com/7272/7633179468_3e19e45a0c_b.jpg)")
                    
                    //'background-image': [ 'https://farm2.staticflickr.com/1261/1413379559_412a540d29_b.jpg']

                    // cy.layout({
                    //     name: 'dagre', 
                    // }).run(); 
                    
                    
                });    

            },

            renderNode(){
                this.$cytoscape.instance.then(cy => {   
                    cy.layout({name: 'dagre'}).run();  
                });    
            },

            preConfig (cytoscape) { 
               
                cytoscape.use(dagre)  

            },
            
            // you can call this method somewhere to trigger the update of the cytoscape canvas content
            cyUpdate () {
                // new nodes and edges
                 
                const cynodes = [
                    { data: { id: 'n0' } },
                    { data: { id: 'n1' } },
                    { data: { id: 'n2' } },
                    { data: { id: 'n3' } },
                    { data: { id: 'n4' } },
                    { data: { id: 'n5' } },
                    { data: { id: 'n6' } },
                    { data: { id: 'n7' } },
                    { data: { id: 'n8' } },
                    { data: { id: 'n9' } },
                    { data: { id: 'n10' } },
                    { data: { id: 'n11' } },
                    { data: { id: 'n12' } },
                    { data: { id: 'n13' } },
                    { data: { id: 'n14' } },
                    { data: { id: 'n15' } },
                    { data: { id: 'n16' } }
                ];

                const cylinks = [
                    { data: { source: 'n0', target: 'n1' } },
                    { data: { source: 'n1', target: 'n2' } },
                    { data: { source: 'n1', target: 'n3' } },
                    { data: { source: 'n4', target: 'n5' } },
                    { data: { source: 'n4', target: 'n6' } },
                    { data: { source: 'n6', target: 'n7' } },
                    { data: { source: 'n6', target: 'n8' } },
                    { data: { source: 'n8', target: 'n9' } },
                    { data: { source: 'n8', target: 'n10' } },
                    { data: { source: 'n11', target: 'n12' } },
                    { data: { source: 'n12', target: 'n13' } },
                    { data: { source: 'n13', target: 'n14' } },
                    { data: { source: 'n13', target: 'n15' } },
                ];

                const _cynodes = [
                            { data: { id: 'jerry', name: 'jerry', height: 200, weight: 80 } },
                            { data: { id: 'elaine', name: 'elaine', height: 190, weight: 70 } },
                            { data: { id: 'kramer', name: 'kramer', height: 180, weight: 60 } },
                            { data: { id: 'george', name: 'george', height: 170, weight: 50 } },

                            { data: { id: 'A', name: 'A', height: 1, weight: 1 } },

                            { data: { id: 'B', name: 'B', height: 2, weight: 2 } },
                            { data: { id: 'C', name: 'C', height: 3, weight: 3 } },

                        ];
                const _cylinks = [
                            { data: { source: 'jerry', target: 'elaine' } }, 
                            { data: { source: 'jerry', target: 'kramer' } },   
                            { data: { source: 'jerry', target: 'george' } }, 

                            { data: { source: 'kramer', target: 'A' } },   

                            { data: { source: 'george', target: 'B' } },  
                            { data: { source: 'george', target: 'C' } },  

                        ];
   
                
                // update the cytoscape instance
                this.$cytoscape.instance.then(cy => {
                    // remove all elements
                    cy.remove(cy.elements())
                    // add the new ones
                    cy.add(_cynodes)
                    cy.add(_cylinks) 

                    
                  
                    // click and double click (simulated) over the nodes
                    cy.on('tap', 'node', function (event) {
                        const data = event.target.data()
                        console.log(JSON.stringify(data));
                    })

                    //cy.panningEnabled( false );

                    cy.layout({
                        name: 'dagre', 
                    }).run();

                    // cy.nodes().positions(function( node, i ){
                    //     return {
                    //         x: (i+10) * 100,
                    //         y: i * 50
                    //     };
                    //     }); 
                    //  cy.layout().run();    

                    // var taller = cy.$('node[height > 190]');
                    // taller.nodes().positions(function( node, i ){
                    //     return {
                    //         x: (i+10) * 100,
                    //         y: i * 50
                    //     };
                    //     }); 
                    // console.log(taller.connectedEdges());


                })
            },

            addNode(){
                var source_id = document.getElementById('sid').value;
                var _id = document.getElementById('mid').value;
                var _name = document.getElementById('mname').value;
 
                //console.log("source:" +source_id + " id:" + _id + " name:"+_name);

                this.$cytoscape.instance.then(cy => {
                    cy.add({
                        data: { id: _id, name: _name }                    
                    });
                    
                    //var source = id;
                    cy.add({
                        data: { 
                            source: source_id,
                            target: _id
                        } 
                    }); 

                    cy.layout({
                        name: 'dagre'
                    }).run();
                })

            },
              
        }

    }
</script>

<style scoped>
 
</style>

