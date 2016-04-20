<?php include('common_header.php');?>
    <div class="pageheader">
      <h2><i class="fa fa-bug"></i> Bug管理 <span>我的Bug列表</span></h2>
      <div class="breadcrumb-wrapper">
        <span class="label">你的位置:</span>
        <ol class="breadcrumb">
          <li><a href="/">我的控制台</a></li>
          <li><a href="/bug">Bug管理</a></li>
          <li class="active">我的Bug列表</li>
        </ol>
      </div>
    </div>
    
    <div class="contentpanel panel-email">
      <div class="row">
        <div class="col-sm-3 col-lg-2">
          <ul class="nav nav-pills nav-stacked nav-email">
              <li class="active"><a href="/bug"><i class="glyphicon glyphicon-inbox"></i> Bug列表</a></li>
              <li><a href="#"><i class="glyphicon glyphicon-star"></i> 星标</a></li>
              <li><a href="#"><i class="glyphicon glyphicon-trash"></i> 已删除</a></li>
          </ul>
          <div class="mb30"></div>
          
          <h5 class="subtitle">快捷方式</h5>
          <ul class="nav nav-pills nav-stacked nav-email mb20">
            <li><a href="#"><i class="glyphicon glyphicon-folder-open"></i> 我负责的</a></li>
            <li><a href="#"><i class="glyphicon glyphicon-folder-open"></i> 我创建的</a></li>
            <li><a href="#"><i class="glyphicon glyphicon-folder-open"></i> 已完成的</a></li>
          </ul>
        </div><!-- col-sm-3 -->
            
            <div class="col-sm-9 col-lg-10">
                
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="pull-right">
                            <?php if ($row['state'] == '0' && ($this->input->cookie('uids') == $row['accept_user'])) {?>
                            <div class="btn-group mr10">
                                <button class="btn btn-sm btn-primary tooltips" type="button" title="确认BUG，你可以调整严重级别" id="checkin" ids="<?php echo $row['id'];?>" data-toggle="modal" data-target="#myModal">确认BUG</button>
                                <button class="btn btn-sm btn-primary tooltips" type="button" title="如果BUG反馈无效，请说明理由" id="checkout" data-toggle="modal" data-target="#myModal2">反馈无效</button>
                            </div>
                            <?php }?>

                            <div class="btn-group mr10">
                                <button class="btn btn-sm btn-white tooltips" type="button" data-toggle="tooltip" title="返回列表"><i class="fa fa-reply"></i></button>
                                <button class="btn btn-sm btn-white tooltips" type="button" data-toggle="tooltip" title="额外信息"><i class="glyphicon glyphicon-exclamation-sign"></i></button>
                                <button class="btn btn-sm btn-white tooltips" type="button" data-toggle="tooltip" title="删除"><i class="glyphicon glyphicon-trash"></i></button>
                            </div>
                        </div><!-- pull-right -->
                        
                        <div class="btn-group mr10">
                            <a class="btn btn-sm btn-<?php if ($pager['prev']) { echo 'white'; } else { echo 'default'; }?> tooltips" href="<?php if ($pager['prev']) { echo '/bug/view/'.$pager['prev']; } else { echo 'javascript:;'; }?>" data-toggle="tooltip" title="阅读上一条"><i class="glyphicon glyphicon-chevron-left"></i></a>
                            <a class="btn btn-sm btn-<?php if ($pager['next']) { echo 'white'; } else { echo 'default'; }?> tooltips" href="<?php if ($pager['next']) { echo '/bug/view/'.$pager['next']; } else { echo 'javascript:;'; }?>" data-toggle="tooltip" title="阅读下一条"><i class="glyphicon glyphicon-chevron-right"></i></a>
                        </div>
                        
                        <div class="read-panel">
                          <div class="media">
                            <div class="pull-left">
                              <div class="face"><img alt="" src="/static/avatar/<?php echo $users[$row['add_user']]['username']?>.jpg" align="absmiddle" title="<?php echo $users[$row['add_user']]['realname'];?>"></div>
                            </div>
                            <div class="media-body">
                              <span class="media-meta pull-right"><?php echo friendlydate($row['add_time']);?></span>
                              <h4 class="text-primary"><?php echo $users[$row['add_user']]['realname'];?></h4>
                              <small class="text-muted">BUG反馈人</small>
                              <h4 class="email-subject"><?php if ($row['level']) {?><?php echo "<strong style='color:#ff0000;' title='".$level[$row['level']]['alt']."'>".$level[$row['level']]['name']."</strong> ";?><?php } ?><?php echo $row['subject'];?></h4>
                              <p><?php echo $row['content'];?></p>
                            </div>
                          </div>
                          <?php if ($row['state'] >=1) {?><div align="center"><span class="badge"><?php echo $users[$row['accept_user']]['realname'].' 已在 '.date("Y-m-d H:i:s", $row['check_time']).' 确认了这个BUG是有效的';?></span></div><?php } ?>
                          <?php if ($row['state'] == -1) {?><div align="center"><span class="badge"><?php echo $users[$row['accept_user']]['realname'].' 已在 '.date("Y-m-d H:i:s", $row['check_time']).' 确认了这个BUG是无效的';?></span></div><?php } ?>
                          <?php
                            if ($rows) {
                              foreach ($rows as $value) {
                          ?>
                          <div class="media" id="comment-<?php echo $value['id'];?>">
                            <div class="pull-left">
                              <div class="face"><img alt="" src="/static/avatar/<?php echo $users[$value['add_user']]['username']?>.jpg" align="absmiddle" title="<?php echo $users[$value['add_user']]['realname'];?>"></div>
                            </div>
                            <div class="media-body">
                              <span class="media-meta pull-right"><?php echo friendlydate($value['add_time']);?><?php if ($value['add_user'] == $this->input->cookie('uids')) {?><br /><a class="del" ids="<?php echo $value['id'];?>" href="javascript:;">删除</a></span><?php } ?>
                              <h4 class="text-primary"><?php echo $users[$value['add_user']]['realname'];?></h4>
                              <small class="text-muted">路人甲</small>
                              <p><?php echo $value['content'];?></p>
                            </div>
                          </div>
                          <?php
                              }
                            }
                          ?>  
                          <div id="box"></div>
                          <div class="media">
                            <div class="pull-left">
                              <div class="face"><img alt="" src="/static/avatar/<?php echo $users[$this->input->cookie('uids')]['username']?>.jpg" align="absmiddle" title="<?php echo $users[$this->input->cookie('uids')]['realname'];?>"></div>
                            </div>
                            <div class="media-body">
                              <textarea id="content" name="content"></textarea>
                              <div class="mb10"></div>
                              <input type="hidden" value="<?php echo $row['id'];?>" id="bug_id" name="bug_id">
                              <button class="btn btn-primary" id="btnSubmit">提交</button>
                            </div>
                          </div>
                        </div><!-- read-panel -->
                        
                    </div><!-- panel-body -->
                </div><!-- panel -->
                
            </div><!-- col-sm-9 -->
            
        </div><!-- row -->
      
    </div><!-- contentpanel -->
    
  </div><!-- mainpanel -->
  
</section>

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">确认BUG</h4>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label class="col-sm-4">当前严重级别</label>
          <div class="col-sm-4">
            <select class="select2" data-placeholder="调整严重级别">
              <option value="1" <?php if ($row['level'] == 1) echo 'selected="selected"';?>>[!]轻微</option>
              <option value="2" <?php if ($row['level'] == 2) echo 'selected="selected"';?>>[!!]轻</option>
              <option value="3" <?php if ($row['level'] == 3) echo 'selected="selected"';?>>[!!!]严重</option>
              <option value="4" <?php if ($row['level'] == 4) echo 'selected="selected"';?>>[!!!!]非常严重</option>
            </select>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
        <button type="button" class="btn btn-primary ajax-btn" data-dismiss="modal">提交</button>
      </div>
    </div><!-- modal-content -->
  </div><!-- modal-dialog -->
</div><!-- modal -->

<!-- Modal -->
<div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">请说明反馈无效的理由</h4>
      </div>
      <div class="modal-body">
        <textarea class="form-control" rows="5" placeholder="请说明反馈无效的理由"></textarea>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
        <button type="button" class="btn btn-primary ajax-btn2" data-dismiss="modal">提交</button>
      </div>
    </div><!-- modal-content -->
  </div><!-- modal-dialog -->
</div><!-- modal -->

<script src="/static/js/jquery-1.11.1.min.js"></script>
<script type="text/javascript" src="/static/simditor-2.3.6/scripts/module.js"></script>
<script type="text/javascript" src="/static/simditor-2.3.6/scripts/uploader.js"></script>
<script type="text/javascript" src="/static/simditor-2.3.6/scripts/hotkeys.js"></script>
<script type="text/javascript" src="/static/simditor-2.3.6/scripts/simditor.js"></script>
<script src="/static/js/jquery-migrate-1.2.1.min.js"></script>
<script src="/static/js/bootstrap.min.js"></script>
<script src="/static/js/modernizr.min.js"></script>
<script src="/static/js/jquery.sparkline.min.js"></script>
<script src="/static/js/toggles.min.js"></script>
<script src="/static/js/jquery.cookies.js"></script>

<script src="/static/js/jquery.datatables.min.js"></script>
<script src="/static/js/select2.min.js"></script>

<script src="/static/js/custom.js"></script>

<script type="text/javascript">
$(function(){
  toolbar = [ 'title', 'bold', 'italic', 'underline', 'strikethrough',
    'color', '|', 'ol', 'ul', 'blockquote', 'code', 'table', '|',
    'link', 'image', 'hr', '|', 'indent', 'outdent' ];
  var editor = new Simditor({
    textarea : $('#content'),
    toolbar : toolbar,  //工具栏
    defaultImage : '/static/simditor-2.3.6/images/image.png', //编辑器插入图片时使用的默认图片
    upload: {
        url: '/admin/upload',
        params: null, //键值对,指定文件上传接口的额外参数,上传的时候随文件一起提交  
        fileKey: 'upload_file', //服务器端获取文件数据的参数名  
        connectionCount: 3,  
        leaveConfirm: '正在上传文件'
      }
  });

  $("#btnSubmit").click(function(){
    content = $("#content").val();
    bug_id = $("#bug_id").val();
    if (!content) {
      editor.focus();
      return false;
    }
    $.ajax({
      type: "POST",
      url: "/bug/coment_add_ajax",
      data: "content="+content+"&bug_id="+bug_id,
      dataType: "JSON",
      success: function(data){
        if (data.status) {
          $("#box").html('<div class="media"><div class="pull-left"><div class="face"><img alt="" src="/static/avatar/'+data.message.username+'.jpg" align="absmiddle" title="'+data.message.realname+'"></div></div><div class="media-body"><span class="media-meta pull-right">'+data.message.addtime+'</span><h4 class="text-primary">'+data.message.realname+'</h4><small class="text-muted">路人甲</small><p>'+data.message.content+'</p></div></div>');
          editor.setValue('');
        } else {
          alert('fail');
        };
      }
    });
  });

  $(".del").click(function(){
    var c = confirm('你确定要删除吗？');
      if(c) {
        id = $(this).attr("ids");
        $.ajax({
          type: "GET",
          url: "/bug/del_comment/"+id,
          dataType: "JSON",
          success: function(data){
            if (data.status) {
              setTimeout(function () {
                $("#comment-"+id).hide();
              }, 500);
            } else {
              alert('fail');
            }
          }
        });
      }
  });

  $(".ajax-btn").click(function(){
    level = $('.select2 option:selected').val();
    bug_id = $("#bug_id").val();
    $.ajax({
      type: "GET",
      dataType: "JSON",
      url: "/bug/checkin/"+bug_id+"/"+level,
      success: function(data){
        if (data.status) {
          location.href = '/bug/view/'+bug_id;
        } else {
          alert('fail');
        } 
      }
    });
  });

  //反馈无效
  $(".ajax-btn2").click(function(){
    content = $('.form-control').val();
    bug_id = $("#bug_id").val();
    $.ajax({
      type: "POST",
      dataType: "JSON",
      url: "/bug/checkout/",
      data: "content="+content+"&bug_id="+bug_id,
      success: function(data){
        if (data.status) {
          location.href = '/bug/view/'+bug_id;
        } else {
          alert('fail');
        } 
      }
    });
  });

  //调整严重级别
  jQuery(".select2").select2({
    width: '100%',
    minimumResultsForSearch: -1
  });

});
</script>

</body>
</html>
