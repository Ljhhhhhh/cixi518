<template>
  <div>
    <x-header :left-options="{showBack: false}">专用计算器</x-header>
    <group title="请输入对应值">
      <x-input v-for="(item, index) in product_info" :key="index" :title="item.name" :placeholder="item.placeholder" novalidate
        :show-clear="false" placeholder-align="right" v-model="item.value" text-align="right" :required="true" type="number"></x-input>
    </group>
    <group title="总计">
      <cell title="平方">{{area}}</cell>
      <cell title="单价">{{price}}</cell>
      <cell title="总额">{{count_price}}</cell>
    </group>
  </div>
</template>
<script>
  import {
    XInput,
    Group,
    XButton,
    Cell,
    XHeader
  } from 'vux'
  export default {
    data () {
      return {
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
        }
      }
    },
    computed: {
      area () {
        if (!this.product_info.length.value || !this.product_info.width.value || !this.product_info.height.value) {
          return 0
        }
        const length = this.product_info.length.value * 1e5
        const width = this.product_info.width.value * 1e5
        const height = this.product_info.height.value * 1e5
        const area = (length + width + 8e5) * (width + height + 4e5) / 1e10
        return area
      },
      price () {
        if (!this.area || !this.product_info.unit.value) {
          return 0
        }
        const unit = this.product_info.unit.value * 1e5
        const price = this.area * unit / 1e5 / 1e4
        return price
      },
      count_price () {
        return this.price * (this.product_info.number.value * 1e5) / 1e5
      }
    },
    components: {
      XInput,
      XButton,
      Group,
      Cell,
      XHeader
    }
  }
</script>
<style lang="less" scoped>


</style>
