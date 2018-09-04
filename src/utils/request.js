import axios from 'axios'
// import store from '@/store'
import {
  getToken
} from '@/utils/auth'
import qs from 'qs'

// create an axios instance
const service = axios.create({
  baseURL: 'http://api.cixi518.com/', /* http://api.cixi518.com/ */
  timeout: 5000, // request timeoutheaders: { 'Content-Type': 'application/x-www-form-urlencoded' },
  transformRequest: [function (data) {
    // data.token = getToken()
    // data.userId = getUserId()
    data = qs.stringify(data)
    return data
  }]
})

// request interceptor
service.interceptors.request.use(config => {
  // Do something before request is sent
  // if (store.getters.token) {
  // 让每个请求携带token-- ['X-Token']为自定义key 请根据实际情况自行修改
  config.headers['X-Token'] = getToken()
  // }
  return config
}, error => {
  // Do something with request error
  console.log(error) // for debug
  Promise.reject(error)
})

// respone interceptor
service.interceptors.response.use(
  response => response.data,
  /**
   * 下面的注释为通过在response里，自定义code来标示请求状态
   * 当code返回如下情况则说明权限有问题，登出并返回到登录页
   * 如想通过xmlhttprequest来状态码标识 逻辑可写在下面error中
   * 以下代码均为样例，请结合自生需求加以修改，若不需要，则可删除
   */
  error => {
    console.log('err' + error) // for debug
    return Promise.reject(error)
  })

export default service
