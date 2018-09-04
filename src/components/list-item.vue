<template>
  <div id="list_container" ref="container">
    <x-header :left-options="{showBack: false}" ref="header" :title="title">
      <span slot="right" @click="h_btn">{{h_btn_text}}</span>
    </x-header>
    <p class="loading" v-show="loadingText.length" ref="loading" :style="loadingStyle">
      <span>{{loadingText}}</span>
      <i class="fa" :class="loadingClass"></i>
    </p>
    <div ref="wrapper" id="list_item_box">
      <div ref="content" id="list_item">
        <slot></slot>
      </div>
    </div>
  </div>
</template>

<script>
  import {
    XHeader
  } from 'vux'
  import BScroll from 'better-scroll'
  import {
    calHei
  } from 'static/js/dom'
  import NoResult from '@/components/no-result'

  export default {
    name: 'list-item',
    props: {
      title: {
        type: String,
        default: ''
      },
      h_btn_text: {
        type: String,
        default: '新建'
      },
      loadingText: {
        type: String,
        default: ''
      },
      loadingStyle: {
        type: Object,
        default: {}
      },
      loadingClass: {
        type: Array,
        default: []
      },
      probeType: {
        type: Number,
        default: 1
      },
      click: {
        type: Boolean,
        default: true
      },
      data: {
        type: Array,
        default: null
      },
      listenScroll: {
        type: Boolean,
        default: true
      },
      pullup: {
        type: Boolean,
        default: false
      },
      pulldown: {
        type: Boolean,
        default: true
      },
      beforeScroll: {
        type: Boolean,
        default: false
      },
      refreshDelay: {
        type: Number,
        default: 200
      }
    },
    data () {
      return {
        scrolling: false
      }
    },
    methods: {
      h_btn () {
        this.$emit('h_click')
      },
      _initPage () {
        calHei(this.$refs.container, this.$refs.wrapper, this.$refs.header)
      },
      _initScroll () {
        this.$refs.content.style.minHeight = parseInt(this.$refs.wrapper.style.height) + 0.5 + 'px'
        if (!this.$refs.wrapper) {
          return
        }
        this.scroll = new BScroll(this.$refs.wrapper, {
          probeType: this.probeType,
          click: this.click
        })

        if (this.listenScroll) {
          let me = this
          this.scroll.on('scroll', (pos) => {
            me.$emit('scroll', pos)
          })
        }

        if (this.pullup) {
          this.scroll.on('touchEnd', () => {
            // if (this.scroll.y <= (this.scroll.maxScrollY + 30)) {
            //
            // }
            this.$emit('touchEnd')
          })
        }
        this.scroll.on('scrollEnd', () => {
          this.$emit('scrollEnd')
          this.$nextTick(() => {
            this.refresh()
          })
        })
        if (this.beforeScroll) {
          this.scroll.on('beforeScrollStart', () => {
            this.$emit('beforeScroll')
          })
        }
      },
      enable () {
        this.scroll && this.scroll.enable()
      },
      disable () {
        this.scroll && this.scroll.disable()
      },
      refresh () {
        this.scroll && this.scroll.refresh()
      },
      scrollTo () {
        this.scroll && this.scroll.scrollTo.apply(this.scroll, arguments)
      },
      scrollToElement () {
        this.scroll && this.scroll.scrollToElement.apply(this.scroll, arguments)
      }
    },
    mounted () {
      setTimeout(() => {
        this._initScroll()
      }, this.refreshDelay)
      this._initPage()
    },
    watch: {
      data () {
        setTimeout(() => {
          this.refresh()
        }, this.refreshDelay)
      }
    },
    components: {
      XHeader,
      NoResult
    }
  }
</script>

<style lang="less" scoped>
  // @import '../../static/styles/reset.less';

  #list_container {
    width: 100%;
    height: 100%;
    overflow: hidden;
  }

  #list_item_box {
    width: 100%;
    overflow-y: hidden;
    background-color: #F5F5F5;
  }

  #list-item {
    width: 100%;
    height: auto;
    background-color: #F5F5F5;
  }

  .loading {
    text-align: center;
    padding: 10px 0;
    position: fixed;
    width: 100%;
    height: auto;
    span {
      display: inline-block;
      color: #3C4C5C;
      vertical-align: middle;
      font-size: 14px;
    }
  }

  .fa {
    font-size: 12px;
  }

</style>
