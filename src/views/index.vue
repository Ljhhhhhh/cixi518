<template>
  <div>
    <div class="main">
      <router-view></router-view>
    </div>
    <tabbar>
      <tabbar-item selected link="/calculator">
        <icon slot="icon" name="calculator" scale="3"></icon>
        <span slot="label">计算器</span>
      </tabbar-item>
      <tabbar-item>
        <icon slot="icon" name="order" scale="3"></icon>
        <span slot="label">订单</span>
      </tabbar-item>
      <tabbar-item link="/component/demo">
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

  .main {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 42px;
  }

</style>
