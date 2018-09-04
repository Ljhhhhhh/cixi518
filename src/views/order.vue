<template>
  <div>
    <div class="container">
      <div class="content">
        <list-item :pullup="true" :title="title" @scroll="scroll" @touchEnd="touchEnd" @scrollEnd="scrollEnd" :loadingText="loadingText"
          :loadingStyle="loadingStyle" :loadingClass="loadingClass" @h_click="addCustomer" h_btn_text="">
          <ul class="ul">
            <li v-for="item in orderList" :key="item.id">
              <div class="box" @click="goDetail(item.order_id)">
                <div class="item_text">
                  <p>客户：{{item.tocustomer.cus_name}}</p>
                  <p>总额：{{getTotalPrice(item.products)}}元</p>
                  <p>创建时间：
                    <span v-html="item.create_time"></span>
                  </p>
                </div>
              </div>
            </li>
          </ul>
          <no-result v-if="!orderList.length" :title="NoResultTitle"></no-result>
        </list-item>
        <router-view></router-view>
        <div class="select" @click="showSelect" v-show="btn_show">
          <img src="static/images/select.png">
        </div>
        <Popup v-model="select.show" position="bottom" should-scroll-top-on-show>
          <popup-header right-text="确认" title="订单筛选" prevent-default @on-click-right="selectCustomer"></popup-header>
          <group>
            <PopupRadio title="请选择客户" v-model="select.cus_id" :options="cusList"></PopupRadio>
            <PopupRadio title="请选择订单状态" v-model="select.ispay" :options="payOptions"></PopupRadio>
          </group>
        </Popup>
      </div>
    </div>
    <tab :selectedIndex="2"></tab>
    <router-view></router-view>
  </div>
</template>
<script>
  import {
    XHeader,
    PopupHeader,
    Popup,
    PopupRadio,
    Group
  } from 'vux'
  import Tab from '@/components/tab'
  import {
    OrderList
  } from '@/api/order'
  import {
    customerList
  } from '@/api/customer'
  import listItem from '@/components/list-item'
  import NoResult from '@/components/no-result'
  import 'font-awesome/css/font-awesome.css'

  export default {
    data () {
      return {
        orderList: [],
        page: 1,
        loadingText: '',
        loadingStyle: {},
        loadingClass: [],
        NoResultTitle: '',
        cusList: [],
        btn_show: true,
        payOptions: [{
          key: -1,
          value: '全部'
        },
        {
          key: 1,
          value: '已付款'
        },
        {
          key: 0,
          value: '未付款'
        }
        ],
        select: {
          show: false,
          cus_id: null,
          ispay: -1
        }
      }
    },
    created () {
      this._getApplyList()
      this.customerList()
    },
    watch: {
      $route (to, from) {
        if (to.name === 'OrderDetail') {
          this.btn_show = false
        } else {
          this.btn_show = true
        }
      }
    },
    computed: {
      title () {
        let title = '订单'
        let name = ''
        let pay = ''
        if (this.select.cus_id) {
          this.cusList.forEach(cus => {
            if (cus.key === this.select.cus_id) {
              name = cus.value + '的'
            }
          })
        }
        if (this.select.ispay > -1) {
          pay = this.select.ispay ? '已付款' : '未付款'
        }
        return name + pay + title
      }
    },
    methods: {
      selectCustomer () {
        this._getApplyList()
        this.select.show = false
      },
      showSelect () {
        this.select.show = true
      },
      goDetail (id) {
        this.$router.push({
          path: '/order/detail',
          query: {
            id
          }
        })
      },
      getTotalPrice (products) {
        let tp = 0
        products.forEach(product => {
          tp += parseFloat(product.count_price)
        })
        return tp.toFixed(2)
      },
      addCustomer () {

      },
      _getApplyList (page, loadmore = false) {
        const params = {
          page,
          customer: this.select.cus_id
        }
        if (this.select.ispay > -1) {
          params.ispay = this.select.ispay ? 1 : 0
        }
        OrderList(params).then(res => {
          this._setData(res, page, loadmore)
        })
      },
      _setData (res, page, loadmore) {
        let newLists = res.data
        if (loadmore) {
          if (newLists.length <= 0) {
            this.page -= 1
            this.loadingText = `没有更多订单`
            this.loadingClass = ['fa-hand-stop-o']
            return
          }
          this.orderList.push(...newLists)
          this.loadingText = '请求成功'
          this.loadingClass = ['fa-check-circle-o']
        } else {
          this.orderList = [...newLists]
          this.loadingClass = ['fa-check-circle-o']
          if (!this.orderList.length) {
            this.NoResultTitle = `暂无订单`
          }
        }
      },
      customerList () {
        this.cusList = [{
          key: null,
          value: '全部客户'
        }]
        customerList().then(res => {
          res.data.forEach(cus => {
            this.cusList.push({
              key: cus.id,
              value: cus.cus_name
            })
          })
        })
      },
      scroll (pos) {
        if (pos.y > 30 && !this.loadingText.length) {
          this.loadingText = '刷新中'
          this.loadingClass = ['fa-spinner', 'fa-spin']
          this.loadingStyle = {
            top: '45px',
            bottom: ''
          }
          this.page = 1
          this._getApplyList(this.page)
          this.loadingText = '刷新成功'
        }
        if (pos.y < -15 && !this.loadingText.length) {
          this.loadingText = '请求中'
          this.loadingClass = ['fa-spinner', 'fa-spin']
          this.loadingStyle = {
            top: '',
            bottom: '53px'
          }
          this.page += 1
          this._getApplyList(this.page, true)
        }
      },
      touchEnd () {
        this.loadingText = ''
      },
      scrollEnd () {
        this.loadingText = ''
      }
    },
    components: {
      Tab,
      XHeader,
      listItem,
      NoResult,
      PopupHeader,
      PopupRadio,
      Popup,
      Group
    }
  }
</script>
<style lang="less" scoped>
  ul {
    width: 100%;
    height: auto;
  }

  li {
    width: 95%;
    height: auto;
    margin: 0 auto;
    padding: 10px 0 5px;

    .box {
      color: #3C4C5C;
      display: flex;
      align-items: center;
      padding: 10px;
      border-radius: 5px;
      background-color: #FFF;
      box-shadow: 0px 1px 2px 2px #EEE;

      .avatar {
        width: 45px;
        min-width: 45px;
        max-width: 45px;
        height: 45px;
        max-height: 45px;
        overflow: hidden;
        margin-right: 15px;
        position: relative;

        &.user {
          border-radius: 50%;
        }

        svg {
          position: absolute;
          top: 0;
          left: 0;
          bottom: 0;
          right: 0;
          margin: auto;
          color: #999;
        }

        img {
          position: absolute;
          display: block;
          width: 85%;
          height: auto;
          top: 50%;
          left: 50%;
          transform: translate(-50%, -50%);
        }
      }

      .item_text {
        font-weight: 300;
        max-width: 75%;

        h5 {
          font-size: 12px;
          line-height: 2em;
        }

        p {
          font-size: 14px;
          line-height: 1.8em;
          text-overflow: ellipsis;
          overflow: hidden;
          white-space: nowrap;

          &.unfinished,
          span.explain {
            color: #fbc700;
          }
        }
      }
    }
  }

  .select {
    position: fixed;
    z-index: 99;
    right: 15px;
    bottom: 60px;
    width: 40px;
    height: 40px;

    img {
      width: 100%;
      height: 100%;
    }
  }

</style>
