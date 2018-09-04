<template>
  <div>
    <div  class="container">
      <!-- <x-header :left-options="{showBack: false}">客户</x-header> -->
      <div class="content">
        <list-item :pullup="true" title="客户" @scroll="scroll" @touchEnd="touchEnd" @scrollEnd="scrollEnd" :loadingText="loadingText"
          :loadingStyle="loadingStyle" :loadingClass="loadingClass" @h_click="customerHandle(0)">
          <ul class="ul">
            <li v-for="item in customerList" :key="item.id"><!-- @click="itemDetail(item)" -->
              <div class="box" @click="customerHandle(item.id)">
                <div class="item_text">
                  <p>姓名：{{item.cus_name}}</p>
                  <p>联系方式：
                    <span v-html="item.cus_telphone"></span>
                  </p>
                </div>
              </div>
            </li>
          </ul>
          <no-result v-if="!customerList.length" :title="NoResultTitle"></no-result>
        </list-item>
        <toast type="cancel" v-model="toastShow" :text="toastMsg" position="bottom"></toast>
        <router-view></router-view>
      </div>
    </div>
    <tab :selectedIndex="3"></tab>
  </div>
</template>
<script>
  import {
    XHeader,
    Toast
  } from 'vux'
  import Tab from '@/components/tab'
  import {
    customerList
  } from '@/api/customer'
  import listItem from '@/components/list-item'
  import NoResult from '@/components/no-result'
  import 'font-awesome/css/font-awesome.css'

  export default {
    data () {
      return {
        customerList: [],
        page: 1,
        loadingText: '',
        loadingStyle: {},
        loadingClass: [],
        NoResultTitle: '',
        toastMsg: ''
      }
    },
    created () {
      this._getApplyList()
    },
    computed: {
      toastShow () {
        return false
      }
    },
    watch: {
      $route (to, from) {
        if (from.name === 'CustomerDetail') {
          this._getApplyList()
        }
      }
    },
    methods: {
      customerHandle (id) {
        this.$router.push({
          path: '/customer/detail',
          query: {
            id
          }
        })
      },
      _getApplyList (page, loadmore = false) {
        const params = {
          page
        }
        customerList(params).then(res => {
          this._setData(res, page, loadmore)
        })
      },
      _setData (res, page, loadmore) {
        let newLists = res.data
        if (loadmore) {
          if (newLists.length <= 0) {
            this.page -= 1
            this.loadingText = `没有更多客户`
            this.loadingClass = ['fa-hand-stop-o']
            return
          }
          this.customerList.push(...newLists)
          this.loadingText = '请求成功'
          this.loadingClass = ['fa-check-circle-o']
        } else {
          this.customerList = [...newLists]
          this.loadingClass = ['fa-check-circle-o']
          if (!this.customerList.length) {
            this.NoResultTitle = `暂无客户`
          }
        }
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
      Toast
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
      &.user{
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
        transform: translate(-50%,-50%);
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
        &.unfinished,span.explain {
          color: #fbc700;
        }
      }
    }
  }
}
</style>
