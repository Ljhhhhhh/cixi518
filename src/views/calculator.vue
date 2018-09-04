<template>
  <div>
    <div class="container">
      <x-header :left-options="{showBack: false}">专用计算器</x-header>
      <group title="请输入对应值">
        <x-input v-for="(item, index) in product_info" :key="index" :title="item.name" :placeholder="item.placeholder"
          :show-clear="true" placeholder-align="right" v-model="item.value" text-align="right"></x-input>
      </group>
      <group title="总计">
        <cell title="平方">{{area}}</cell>
        <cell title="单价">￥{{price}}</cell>
        <cell title="总额">￥{{count_price}}</cell>
      </group>

      <group>
        <p class="align-center">
          <x-button type="primary" :disabled="btn_disabled" class="width50" @click.native="addToOrder">添加到订单</x-button>
        </p>
        <div v-if="new_order.length > 0" class="order">
          <x-table>
            <thead>
              <tr>
                <td>序号</td>
                <td>产品规格</td>
                <td>单价</td>
                <td>数量</td>
                <td>总价</td>
              </tr>
            </thead>
              <tr v-for="(item, index) in new_order" :key="index">
                <td @click="removeProduct(index)">
                  <badge :text="index+1"></badge>
                </td>
                <td>{{item.spec}}</td>
                <td>￥{{item.price}}</td>
                <td>{{item.number}}</td>
                <td>￥{{item.count_price}}</td>
              </tr>
              <tr class="highlight">
                <td>合计</td>
                <td></td>
                <td></td>
                <td>{{total.allNum}}</td>
                <td>￥{{total.allCountPrice}}</td>
              </tr>
          </x-table>
          <popup-radio title="添加此订单到客户" :options="customer_list" v-model="customer_checked" placeholder="请选择客户"></popup-radio>
          <!-- 调整cus_name -->
          <p class="align-center">
            <x-button type="primary" :disabled="customer_disabled" class="width50" @click.native="confirmShow">保存订单</x-button>
          </p>
        </div>
      </group>

    </div>
    <div v-transfer-dom>
      <confirm v-model="showConfirm" title="请确认订单信息" @on-confirm="onConfirm">
        <p>客户姓名：<span class="highlight">{{customer_checked_name}}</span></p>
        <p>{{new_order.length}}种规格的{{total.allNum}}个产品</p>
        <p>共计<span class="highlight">￥{{total.allCountPrice}}</span>元</p>
      </confirm>
    </div>
    <toast v-model="toast.show">{{toast.msg}}</toast>
    <tab :selectedIndex="1"></tab>
  </div>
</template>
<script>
  import {
    XInput,
    Group,
    XButton,
    Cell,
    XHeader,
    PopupRadio,
    ViewBox,
    Confirm,
    TransferDomDirective as TransferDom,
    XTable,
    Badge,
    Toast
  } from 'vux'
  import Tab from '@/components/tab'
  import {
    customerList
  } from '@/api/customer'
  import {
    getUserId
  } from '@/utils/auth'
  import { createOrder } from '@/api/order'
  export default {
    directives: {
      TransferDom
    },
    data () {
      return {
        showConfirm: false,
        product_info: {
          length: {
            name: '长度',
            placeholder: '请输入长度',
            value: null
          },
          width: {
            name: '宽度',
            placeholder: '请输入宽度',
            value: null
          },
          height: {
            name: '高度',
            placeholder: '请输入高度',
            value: null
          },
          unit: {
            name: '平方/元',
            placeholder: '请输入价格',
            value: null
          },
          number: {
            name: '数量',
            placeholder: '请输入数量',
            value: null
          }
        },
        customer_list: [],
        customer_checked: '请选择',
        new_order: [],
        toast: {
          show: false,
          msg: ''
        }
      }
    },
    mounted () {
      this.getCustomer()
    },
    watch: {
      $route (to, from) {
        this._getApplyList()
      }
    },
    methods: {
      getCustomer () {
        const params = {
          pageSize: 10000
        }
        customerList(params).then(res => {
          this.customer_list = []
          res.data.forEach(item => {
            this.customer_list.push({
              key: item.id,
              value: item.cus_name
            })
          })
        })
      },
      removeProduct (index) {
        this.new_order.splice(index, 1)
      },
      addToOrder () {
        this.new_order.push({
          pro_long: this.product_info.length.value,
          pro_wide: this.product_info.width.value,
          pro_high: this.product_info.height.value,
          spec: `${this.product_info.length.value}X${this.product_info.width.value}X${this.product_info.height.value}`,
          price: this.price,
          number: this.product_info.number.value,
          count_price: this.count_price,
          pre_price: this.product_info.unit.value
        })
        let keys = Object.keys(this.product_info)
        keys.forEach(item => {
          this.product_info[item].value = ''
        })
      },
      onConfirm () {
        const data = {}
        data.customer_id = this.customer_checked
        data.createbyid = getUserId()
        data.product_info = JSON.stringify(this.new_order)
        createOrder(data).then(res => {
          if (res.code === 200) {
            this.new_order = []
            this.toast.show = true
            this.toast.msg = res.msg
          }
        })
      },
      confirmShow () {
        this.showConfirm = true
      }
    },
    computed: {
      customer_checked_name () {
        let name = ''
        this.customer_list.forEach((item, index) => {
          if (this.customer_checked === item.key) {
            name = this.customer_list[index].value
          }
        })
        return name
      },
      area () {
        if (!this.product_info.length.value || !this.product_info.width.value || !this.product_info.height.value) {
          return 0
        }
        const length = this.product_info.length.value * 1e8
        const width = this.product_info.width.value * 1e8
        const height = this.product_info.height.value * 1e8
        const area = ((length + width + 8e8) * (width + height + 4e8) / 1e16).toFixed(0)
        return area
      },
      price () {
        if (!this.area || !this.product_info.unit.value) {
          return 0
        }
        const unit = this.product_info.unit.value * 1e8
        const price = (this.area * unit / 1e8 / 1e4).toFixed(2)
        return price
      },
      count_price () {
        return (this.price * (this.product_info.number.value * 1e8) / 1e8).toFixed(2)
      },
      btn_disabled () {
        if (this.count_price && this.count_price > 0) {
          return false
        }
        return true
      },
      customer_disabled () {
        if (this.customer_checked !== '请选择') {
          return false
        }
        return true
      },
      total () {
        let allNum = 0
        let allCountPrice = 0
        if (this.new_order.length) {
          this.new_order.forEach(item => {
            allNum += parseFloat(item.number)
            allCountPrice += parseFloat(item.count_price)
          })
        }
        const total = {
          allNum,
          allCountPrice: allCountPrice.toFixed(2)
        }
        return total
      }
    },
    components: {
      XInput,
      XButton,
      Group,
      Cell,
      XHeader,
      PopupRadio,
      ViewBox,
      Tab,
      Confirm,
      XTable,
      Badge,
      Toast
    }
  }
</script>
<style lang="less" scoped>
  .width50 {
    width: 50%;
    margin: 10px auto;
  }

  .align-center {
    text-align: center;
  }

  .order .weui-cell:before {
    display: none;
  }

</style>
