<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>选号助手</title>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/layui/2.9.7/css/layui.css">
</head>
<body>
<!-- 你的 HTML 代码 -->
<blockquote class="layui-elem-quote layui-text">
  1、抽奖活动一天500张票默认抽5次，如果需要可以自行修改指定次数<br>
  2、建议每个人每天生成一次就好，然后按照给出的数字进行下注，这样我们的中奖概率会更大<br>
  3、随机数池早晚8点清空
</blockquote>
              
<fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
  <legend>选号</legend>
</fieldset>
<form class="layui-form" > <!-- 提示：如果你不想用form，你可以换成div等任何一个普通元素 -->
  <div class="layui-form-item">
    <label class="layui-form-label">生成个数</label>
    <div class="layui-input-block">
      <input type="number" name="num"  autocomplete="off" class="layui-input" value=5 >
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
      <button class="layui-btn" lay-submit lay-filter="*">生成</button>
      <button type="reset" class="layui-btn layui-btn-primary">重置</button>
    </div>
  </div>
</form>
<fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
  <legend>已生成幸运数：</legend>
</fieldset>

<blockquote class="layui-elem-quote layui-text">
 <div id = "luckNumberText" style="word-break:break-all; font-size: 30px;color: #b74a29;"></div>
</blockquote>

<script src="https://cdnjs.cloudflare.com/ajax/libs/layui/2.9.7/layui.js"></script>
<script>

function setLuckNumber(luckNumber){

  var date = new Date();
  var hours = date.getHours();
  if(hours >=8 && hours < 20){
    date.setHours(20,0,0,0);
  } else {
    date.setDate(date.getDate() + 1);
    date.setHours(8,0,0,0);
  }

  const obj ={ val:luckNumber, exp:date.getTime() };
  localStorage.setItem('luckNumber', JSON.stringify(obj));

}


function getLuckNumber(){
  var luckNumber = '';
  var date = new Date();
  const str = localStorage.getItem('luckNumber');
  if(str != "" && str!= undefined){
    const obj = JSON.parse(str);
    if(date.getTime() < obj.exp){
      luckNumber = obj.val;
    }
  }
  return luckNumber;
}

layui.use(['form','jquery','layer'], function(){
    var layer = parent.layer === undefined ? layui.layer : top.layer;

  //各种基于事件的操作，下面会有进一步介绍
  var $ = layui.jquery;
    $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });
  $(document).ready(function(){
    var luckNumber = getLuckNumber();
    if(luckNumber != ""){
      setLuckNumberText(luckNumber);
    }
  });

  function setLuckNumberText(str){
    $("#luckNumberText").html(str);
  }
  var form = layui.form;
  form.on('submit(*)', function(data){
    console.log(data);

    if(parseInt(data.field.num) > 15){
      layer.alert("一次最多生成15个");
      return false;
    }
    if(parseInt(data.field.min) >= parseInt(data.field.max) || parseInt(data.field.max) - parseInt(data.field.min) < parseInt(data.field.num)){
      layer.alert("参数有误");
      return false;
    }


    var luckNumber = getLuckNumber();


     $.ajax({
        type: 'POST',
        url: '/safeTickect',
        data:{
            lucknumber: luckNumber,  //主键
            num: parseInt(data.field.num),  //主键
            min: parseInt(data.field.min),  //主键
            max: parseInt(data.field.max),  //主键
        },
        dataType: "json",
        success: function (ret) {//        

            if(ret.msg != ""){
                layer.alert(ret.msg);
              return 1;
            }
            console.log(ret);

            //保存到浏览器缓存
            setLuckNumber(ret.number);  

            layer.open({
                type: 1
                ,title: false //不显示标题栏
                ,closeBtn: false
                ,shade: 0.8
                ,id: 'LAY_layuipro' //设定一个id，防止重复弹出
                 ,btn: '知道了'
                ,btnAlign: 'c'
                ,content: '<div style="padding: 50px; line-height: 22px; background-color: #393D49; color: #fff;">幸运号码：'+ret.number+'<br>当前阶段总发放号码数：'+ret.count+'</div>'
                 ,yes: function(){
                    layer.closeAll();
                    setLuckNumberText(ret.number);

                  }
                
              });
            }
        });
    return false;
  });

});

</script> 
</body>
</html>