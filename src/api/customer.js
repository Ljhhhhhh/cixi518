import request from '@/utils/request'
import {
  getUserId
} from '@/utils/auth'
export function customerList (query) {
  if (!query) {
    query = {}
  }
  query.createbyid = getUserId()
  return request({
    url: '/index/customer',
    method: 'get',
    params: query
  })
}

export function customerDetail (id) {
  return request({
    url: '/index/customer/read/',
    method: 'get',
    params: {
      id
    }
  })
}

export function createCustomer (data) {
  data.createbyid = getUserId()
  return request({
    url: '/index/customer/save/',
    method: 'post',
    data
  })
}

export function updateCustomer (data, id) {
  return request({
    url: '/index/customer/update/?id=' + id,
    method: 'put',
    data
  })
}
