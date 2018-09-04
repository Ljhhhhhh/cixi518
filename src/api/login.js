import request from '@/utils/request'

export function loginIn (username, password) {
  const data = {
    username,
    password
  }
  return request({
    url: '/index/login/login',
    method: 'post',
    data
  })
}
