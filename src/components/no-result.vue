<template>
  <div class="no-result">
    <div class="no-result-icon">
      <img src="static/images/blank.png">
      <p class="no-result-text">{{title}}</p>
    </div>
  </div>
</template>
<script>
  export default {
    props: {
      title: {
        type: String,
        default: ''
      }
    }
  }
</script>
<style scoped lang="less" scoped>
  .no-result{
    width: 100%;
    height: auto;
    text-align: center;
    position: absolute;
    top: 35%;
    left: 0;
    transform: translateY(-50%);
    .no-result-icon{
      position: relative;
      p{
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate3D(-50%,-50%,0);
        color: #a3a3a3;
      }
    }
  }
</style>
