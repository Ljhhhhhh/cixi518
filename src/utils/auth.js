import Cookies from 'js-cookie'

const TokenKey = 'Admin-Token'
const UserID = 'UserId'

export function getToken () {
  return Cookies.get(TokenKey)
}

export function setToken (token) {
  return Cookies.set(TokenKey, token)
}

export function removeToken () {
  return Cookies.remove(TokenKey)
}

export function getUserId () {
  return Cookies.get(UserID)
}

export function setUserId (id) {
  return Cookies.set(UserID, id)
}
