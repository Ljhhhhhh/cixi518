<template>
  <transition name="slide">
    <div class="content">
      <x-header title="订单详情"></x-header>
      <div class="main">
        <div class="basic_info">
          <p><span>订单总额：</span><span>{{order_total_price}}元</span></p>
          <p><span>创建时间：</span><span>{{create_time}}</span></p>
          <p><span>订单编号：</span><span>{{order_number}}</span></p>
        </div>
        <div class="customer_info">
          <p><span>客户姓名：</span><span>{{customer_info.cus_name}}</span></p>
          <p><span>客户地址：</span><span>{{customer_info.cus_address}}</span></p>
          <p><span>客户电话：</span><span>{{customer_info.cus_telphone}}</span></p>
        </div>
        <div class="product_info">
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
            <tr v-for="(item, index) in product_list" :key="index">
              <td>{{index}}</td>
              <td>{{item.spec}}</td>
              <td>￥{{item.price}}</td>
              <td>{{item.number}}</td>
              <td>￥{{item.count_price}}</td>
            </tr>
            <tr class="highlight">
              <td>合计</td>
              <td></td>
              <td></td>
              <td>{{order_total_number}}</td>
              <td>￥{{order_total_price}}</td>
            </tr>
          </x-table>
        </div>
        <group class="ispay">
          <x-switch v-model="ispay" title="是否已收款" prevent-default @on-click="isPayClick"></x-switch>
        </group>
      </div>
      <confirm v-model="confirm.show" :title="confirm.msg" @on-confirm="onConfirm"></confirm>
    </div>
  </transition>
</template>
<script>
  import {
    XHeader,
    XTable,
    XSwitch,
    Group,
    Confirm
  } from 'vux'
  import {
    OrderDetail,
    setPay
  } from '@/api/order'
  export default {
    data () {
      return {
        customer_info: {},
        product_list: [],
        create_time: '',
        order_number: '',
        order_total_price: 0,
        order_total_number: 0,
        ispay: false,
        confirm: {
          show: false,
          msg: ''
        }
      }
    },
    created () {
      this.getDetail()
    },
    methods: {
      getDetail () {
        const query = {
          order_id: this.$route.query.id
        }
        OrderDetail(query).then(res => {
          this.customer_info = res.data.tocustomer
          this.product_list = res.data.products
          this.create_time = res.data.create_time
          this.order_number = res.data.order_number
          this.ispay = res.data.ispay === 1
          this.product_list.forEach(item => {
            this.order_total_number += parseInt(item.number)
            this.order_total_price += parseFloat(item.count_price)
            item.spec = `${item.pro_long}X${item.pro_wide}X${item.pro_high}`
          })
          this.order_total_price = this.order_total_price.toFixed(2)
        })
      },
      isPayClick (newVal, oldVal) {
        if (oldVal) {
          this.confirm.msg = '确认修改为暂未收款吗？'
        } else {
          this.confirm.msg = '确认修改为已收款吗？'
        }
        this.confirm.show = true
      },
      onConfirm () {
        this.ispay = !this.ispay
        const data = {
          order_id: this.$route.query.id,
          ispay: this.ispay ? 1 : 0
        }
        setPay(data).then(res => {
          console.log(res)
        })
      }
    },
    components: {
      XHeader,
      XTable,
      XSwitch,
      Group,
      Confirm
    }
  }
</script>
<style lang="less" scoped>
  .main {
    background: #FFF;
    position: absolute;
    top: 46px;
    bottom: 56px;
    left: 0;
    right: 0;
    overflow: auto;
  }

  .basic_info,
  .customer_info,
  .product_info,
  .ispay {
    margin: 10px;
    background: #F5F5F5;
    padding: 15px;
    border-radius: 5px;
    box-shadow: -3px 3px #d3d3d3;

    p {
      line-height: 35px;
      text-indent: 0.5em;
      position: relative;

      &::before {
        content: '';
        display: inline-block;
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        margin: auto;
        width: 3px;
        height: 18px;
        background: #09BB07;
      }
    }
  }

</style>
