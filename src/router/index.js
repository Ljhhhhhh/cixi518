import Vue from 'vue'
import Router from 'vue-router'
import Index from '@/views/index'
import Calculator from '@/views/calculator'

Vue.use(Router)

export default new Router({
  routes: [
    {
      path: '/',
      redirect: '/calculator'
    },
    {
      path: '/',
      name: 'index',
      component: Index,
      children: [{
        path: 'calculator',
        name: 'calculator',
        component: Calculator
      }
      ]
    }
  ]
})
