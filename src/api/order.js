import request from '@/utils/request'
import {
  getUserId
} from '@/utils/auth'

export function OrderList (query) {
  query.createbyid = getUserId()
  return request({
    url: '/index/order',
    method: 'get',
    params: query
  })
}

export function OrderDetail (query) {
  return request({
    url: '/index/order/detail',
    method: 'get',
    params: query
  })
}

export function createOrder (data) {
  return request({
    url: '/index/order/save/',
    method: 'post',
    data
  })
}

export function setPay (data) {
  return request({
    url: '/index/order/setpay/',
    method: 'post',
    data
  })
}
