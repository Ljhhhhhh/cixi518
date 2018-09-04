<template>
  <div>
    <img class="login_bac" src="static/images/login_bac.png">
    <form class="form">
      <input type="text" name="username" placeholder="用户名" v-model="username">
      <input type="password" name="password" placeholder="密码" v-model="password">
      <button type="submit" @click.prevent="login">登录</button>
    </form>
    <p class="copyright"></p>
    <popup v-model="popup_show" position="top" :show-mask="false">
      <div class="popup">{{prompt}}</div>
    </popup>
  </div>
</template>
<script>
  import {
    Popup
  } from 'vux'
  import {
    loginIn
  } from '@/api/login'
  import {
    setToken, setUserId
  } from '@/utils/auth'
  export default {
    data () {
      return {
        username: '',
        password: '',
        popup_show: false,
        prompt: ''
      }
    },
    methods: {
      login () {
        loginIn(this.username, this.password).then((res) => {
          if (res.code === 200) {
            setToken(res.data.token)
            setUserId(parseInt(res.data.id))
            console.log(123)
            this.$router.push({
              path: '/'
            })
          } else {
            if (!this.username || !this.password) {
              this.prompt = '请输入用户名和密码'
            } else {
              this.prompt = '用户名或者密码错误'
            }
            this.popup_show = true
          }
        })
      }
    },
    components: {
      Popup
    },
    watch: {
      popup_show (val) {
        if (val) {
          setTimeout(() => {
            this.popup_show = false
          }, 1000)
        }
      }
    }
  }
</script>
<style lang="less" scoped>
  .login_bac {
    width: 100%;
    height: auto;
  }

  .form {
    width: 100%;
    height: auto;
    text-align: center;
    input {
      width: 90%;
      height: 45px;
      line-height: 45px;
      outline: none;
      border: none;
      border-bottom: 2px solid #455a6f;
      color: #455a6f;
      text-indent: 1.5em;
      font-size: 22px;
      margin-top: 15px;
    }
    button {
      margin-top: 35px;
      width: 90%;
      background-color: #455a6f;
      border-radius: 10px;
      color: #FFF;
      font-size: 20px;
      height: 45px;
      line-height: 45px;
      outline: none;
      border: none;
      letter-spacing: 0.3em;
    }
  }

  .other_login {
    text-align: center;
    p {
      margin-top: 30px;
      text-align: center;
      color: #707070;
      span {
        display: inline-block;
        border-bottom: 1px solid #707070;
        transform: scaleY(0.5);
        height: 0;
        width: 80px;
        vertical-align: middle;
        margin: 0 20px;
      }
    }
    a {
      display: inline-block;
      margin: 10px 20px;
      img {
        width: 50px;
        height: auto;
        ;
      }
    }
  }

  p.copyright {
    width: 100%;
    height: 30px;
    line-height: 30px;
    margin-top: 80px;
    text-align: center;
    color: #707070;
    font-weight: 500;
  }

  .popup {
    background-color: #ff0000;
    color: #FFF;
    text-align: center;
    padding: 15px;
  }

</style>
