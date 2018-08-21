import VueRouter from 'vue-router';

let routes = [
    {
        path: '/',
        component: require('./views/Home')
    },
    {
        path: '/posts', 
        component: require('./views/posts/PostMain'),
        
        children: [
            {
                path: '/',
                component: require('./views/posts/PostList'), 
                props: { pageView: 'index'}
            },
            {
                path: 'add',
                component: require('./views/posts/PostForm'), 
                props: { pageView: 'add'}
            },
            {
                path: ':rid',
                component: require('./views/posts/PostForm'),
                props: { default: true, pageView:'show' }
            },
            {
                path: ':rid/edit',
                component: require('./views/posts/PostForm'),
                props: { default: true, pageView:'edit' }
            }
        ]
    }, 
    {
        path: '/members',
        component: require('./views/members/MemberMain'),
        
        children: [
            {
                path: '/',
                component: require('./views/members/MemberList'), 
                props: { pageView: 'index'}
            },
            {
                path: 'add',
                component: require('./views/members/MemberForm'), 
                props: { pageView: 'add'}
            },
            {
                path: ':rid',
                component: require('./views/members/MemberForm'),
                props: { default: true, pageView:'show' }
            },
            {
                path: ':rid/edit',
                component: require('./views/members/MemberForm'),
                props: { default: true, pageView:'edit' }
            }, 
        ]
    },
    {
        path: '/products',
        component: require('./views/products/ProductMain'),
        
        children: [
            {
                path: '/',
                component: require('./views/products/ProductList'), 
                props: { pageView: 'index'}
            },
            {
                path: 'add',
                component: require('./views/products/ProductForm'), 
                props: { pageView: 'add'}
            },
            {
                path: ':rid',
                component: require('./views/products/ProductForm'),
                props: { default: true, pageView:'show' }
            },
            {
                path: ':rid/edit',
                component: require('./views/products/ProductForm'),
                props: { default: true, pageView:'edit' }
            }
        ]
    },
    {
        path: '/membership',
        component: require('./views/members/MembershipTree'), 
    },
    
    {
        path: '/inventory',
        component: require('./views/inventory/InventoryMain')
    },
    {
        path: '/transactions',
        component: require('./views/transactions/TransactionMain'),
        
        children: [
            {
                path: '/',
                component: require('./views/transactions/TransactionList'), 
                props: { pageView: 'index'}
            },
            {
                path: 'add',
                component: require('./views/transactions/TransactionAddForm'), 
                props: { pageView: 'add'}
            },
            {
                path: ':rid',
                component: require('./views/transactions/TransactionForm'),
                props: { default: true, pageView:'show' }
            } 
        ]
    },
    {
        path: '/about',
        component: require('./views/About')
    }            

]

export default new VueRouter({
    routes,
    linkActiveClass: 'is-active',
    mode: 'history'
})