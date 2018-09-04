<template>
  <transition name="slide">
    <div class="content">
      <x-header title="编辑客户信息"></x-header>
      <div class="main">
        <group>
          <x-input title="客户姓名" text-align="right" :required="true" v-model="customer_info.cus_name" placeholder="请输入姓名"></x-input>
          <x-input title="客户号码" text-align="right" :required="true" v-model="customer_info.cus_telphone" placeholder="请输入号码"></x-input>
          <x-input title="联系地址" text-align="right" v-model="customer_info.cus_address" placeholder="请输入地址"></x-input>
          <x-input title="邮箱地址" text-align="right" v-model="customer_info.cus_email" placeholder="请输入邮箱"></x-input>
          <datetime title="客户生日" v-model="customer_info.cus_birthday" :min-year="1930" :max-year="2010"></datetime>
        </group>
        <group>
          <x-button @click.native="submitCustomer" type="primary" :disabled="btn_disabled">提交</x-button>
        </group>
      </div>
      <toast v-model="toast.show" :type="toast.type" :text="toast.msg"></toast>
    </div>
  </transition>
</template>
<script>
  import {
    createCustomer,
    updateCustomer,
    customerDetail
  } from '@/api/customer'
  import {
    Group,
    Cell,
    XInput,
    Datetime,
    XHeader,
    XButton,
    Toast
  } from 'vux'
  export default {
    created () {
      this.cusId = this.$route.query.id
      if (this.cusId > 0) {
        this.getCustomerInfo(this.cusId)
      }
    },
    data () {
      return {
        cusId: 0,
        customer_info: {},
        toast: {
          msg: '',
          show: false
        }
      }
    },
    computed: {
      btn_disabled () {
        if (this.customer_info && this.customer_info.cus_name && this.customer_info.cus_telphone) {
          return false
        } else {
          return true
        }
      }
    },
    methods: {
      getCustomerInfo (id) {
        customerDetail(id).then(res => {
          this.customer_info = res.data
        })
      },
      submitCustomer () {
        let handle = ''
        if (this.cusId === 0) {
          this.toast.msg = '新建成功'
          handle = createCustomer
        } else {
          this.toast.msg = '更新成功'
          handle = updateCustomer
        }
        console.log(handle)
        handle(this.customer_info, this.cusId).then(res => {
          console.log(res)
          if (res.code === 200) {
            this.toast.type = 'success'
          } else {
            this.toast.type = 'cancel'
          }
          this.toast.show = true
          setTimeout(() => {
            this.$router.back()
          }, 1000)
        })
      }
    },
    components: {
      Group,
      Cell,
      XInput,
      Datetime,
      XHeader,
      XButton,
      Toast
    }
  }
</script>
<style lang="less" scoped>
  .main {
    background: #FFF;
    position: absolute;
    top: 46px;
    bottom: 0;
    left: 0;
    right: 0;
    overflow: auto;
  }

</style>
