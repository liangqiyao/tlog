<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <title>开始使用 layui</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/layui/2.9.7/css/layui.css">
</head>
<body>
 
<!-- 你的 HTML 代码 -->

<form class="layui-form"> <!-- 提示：如果你不想用form，你可以换成div等任何一个普通元素 -->
  <div class="layui-form-item">
    <label class="layui-form-label">生成个数</label>
    <div class="layui-input-block">
      <input type="number" name="num"  autocomplete="off" class="layui-input" value=5>
    </div>
  </div>
  <div class="layui-form-item">
    <label class="layui-form-label">最小值</label>
    <div class="layui-input-block">
      <input type="number" name="min"  autocomplete="off" class="layui-input" value=200>
    </div>
  </div>
  <div class="layui-form-item">
    <label class="layui-form-label">最大值</label>
    <div class="layui-input-block">
      <input type="number" name="max"  autocomplete="off" class="layui-input" value=2000>
    </div>
  </div>



  <div class="layui-form-item">
    <div class="layui-input-block">
      <button class="layui-btn" lay-submit lay-filter="*">立即提交</button>
      <button type="reset" class="layui-btn layui-btn-primary">重置</button>
    </div>
  </div>
</form>

<script src="https://cdnjs.cloudflare.com/ajax/libs/layui/2.9.7/layui.js"></script>
<script>
layui.use(['layer', 'form'], function(){
  var layer = layui.layer
  ,form = layui.form;
  
});
</script> 
</body>
</html>