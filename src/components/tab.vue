<template>
  <div>
    <tabbar>
      <tabbar-item link="/calculator" :selected="selectedIndex===1">
        <icon slot="icon" name="calculator" scale="3"></icon>
        <span slot="label">计算器</span>
      </tabbar-item>
      <tabbar-item link="/order" :selected="selectedIndex===2">
        <icon slot="icon" name="order" scale="3"></icon>
        <span slot="label">订单</span>
      </tabbar-item>
      <tabbar-item link="/customer" :selected="selectedIndex===3">
        <icon slot="icon" name="customer" scale="3"></icon>
        <span slot="label">客户</span>
      </tabbar-item>
    </tabbar>
  </div>
</template>

<script>
  import {
    Tabbar,
    TabbarItem
  } from 'vux'

  export default {
    props: {
      selectedIndex: {
        type: Number,
        required: true
      }
    },
    data () {
      return {}
    },
    components: {
      Tabbar,
      TabbarItem
    }
  }
</script>

<style lang="less" scoped>
  .weui-bar__item {
    .weui-tabbar__icon {
      svg {
        color: #999;
      }
    }
  }

  .weui-bar__item_on {
    .weui-tabbar__icon {
      svg {
        color: #09BB07;
      }
    }
  }

</style>
