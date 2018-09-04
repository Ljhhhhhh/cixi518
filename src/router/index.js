import Vue from 'vue'
import Router from 'vue-router'
// import Index from '@/views/index'
import Login from '@/views/login'
import Calculator from '@/views/calculator'
import Customer from '@/views/customer'
import Order from '@/views/order'
import OrderDetail from '@/views/order_detail'
import CustomerDetail from '@/views/customer_detail'

Vue.use(Router)

export default new Router({
  routes: [
    {
      path: '/',
      redirect: '/calculator'
    },
    {
      path: '/calculator',
      name: 'calculator',
      component: Calculator
    },
    {
      path: '/customer',
      name: 'customer',
      component: Customer,
      children: [{
        path: 'detail',
        name: 'CustomerDetail',
        component: CustomerDetail
      }]
    },
    {
      path: '/order',
      name: 'order',
      component: Order,
      children: [
        {
          path: 'detail',
          name: 'OrderDetail',
          component: OrderDetail
        }
      ]
    },
    {
      path: '/login',
      name: 'login',
      component: Login
    }
  ]
})
